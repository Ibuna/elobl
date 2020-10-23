@extends('layouts.modernbusinesswithoutheader')

@section('content')

<!-- D3Pie -->
<script src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.4/d3.min.js"></script>
<script src="../js/d3pie.min.js"></script>

<!-- profil images -->
<div class="container-fluid" >
    <div class="row col-md-12 col-xs-12" style="height: auto; text-align: center;">
        <div style="position:relative; z-index:-1;">
            <picture>         
                <img
                sizes="(max-width: 964px) 100vw, 964px"
                srcset="
                ../images/design/profile_big_aszdcl_c_scale,w_50.png 50w,
                ../images/design/profile_big_aszdcl_c_scale,w_282.png 282w,
                ../images/design/profile_big_aszdcl_c_scale,w_428.png 428w,
                ../images/design/profile_big_aszdcl_c_scale,w_528.png 528w,
                ../images/design/profile_big_aszdcl_c_scale,w_623.png 623w,
                ../images/design/profile_big_aszdcl_c_scale,w_704.png 704w,
                ../images/design/profile_big_aszdcl_c_scale,w_823.png 823w,
                ../images/design/profile_big_aszdcl_c_scale,w_964.png 964w"
                src="profile_big_aszdcl_c_scale,w_964.png"
                alt="big profile image" class="center-block">

            </picture>
        </div>
        <div style="position:absolute; top: 0; left: 0; bottom: 0; right: 0; z-index:-1;">
            <picture>             
                <img
                sizes="(max-width: 964px) 100vw, 964px"
                srcset="
                ../images/design/profile_small_rv17y8_c_scale,w_50.png 50w,
                ../images/design/profile_small_rv17y8_c_scale,w_964.png 964w"
                src="profile_small_rv17y8_c_scale,w_964.png"
                alt="small profile image" class="center-block">
            </picture>
        </div>
    </div>

<!-- player info -->

    <div class="row text-center col-md-12 col-xs-12" style="position: relative;">
        <div class="panel panel-default" >
            <div class="panel-heading">
                @if($gender == 0)
                    <h1><i class="mdi mdi-gender-female"></i>  {{$nickname}}</h1>
                @else
                    <h1><i class="mdi mdi-gender-male"></i>  {{$nickname}}</h1>
                @endif
            </div>
            <div class="panel-body">
                
                <form class="form-inline" method="post" action="/playerstats/{{$nickname}}">
                    {!! csrf_field() !!} 
                    <select class="form-control" name="season">
                        
                        <option value="alltime">{{ trans('messages.all_time_ranking') }}</option>
                        
                        @foreach($seasons as $season)
                            <?php 
                                if($season->season_id == $currentseason){
                                    $selected = 'selected="selected"';
                                }
                                elseif($currentseason == null && $season->active == 1){
                                    $selected = 'selected="selected"';
                                }
                                else{
                                    $selected = '';
                                }
                            ?>
                            <option {{$selected}} value="{{$season->season_id}}">{{$season->name}}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">{{ trans('messages.chooseseason') }}</button>
                </form>
                
                <h3>{{round($elo,0)}}</h3>
                <h5>{{$city}}</h5>
            </div>
            <!--<p>
                 TODO: add gender sign 
                {{$first_name}} {{$last_name}}
            </p>-->
        </div>
    </div> 
</div> 

<!-- pie charts -->
<div class="row text-center" style="top: 0; left: 0; bottom: 0; right: 0; position: relative;">
    <div id="winpercentage" class="col-md-4 col-xs-12"></div>

    <div id="setpercentage"  class="col-md-4 col-xs-12"></div>

    <div id="pointpercentage"  class="col-md-4 col-xs-12"></div>
</div>

<div id="ranking_trend" class="col-md-12 col-xs-12" style="height: 240px; margin-top: 40px"></div>
<div id="elo_trend" class="col-md-12 col-xs-12" style="height: 240px;"></div>

<script>
var pie = new d3pie("winpercentage", {
"header": {
"title": {
"text": "{{trans('messages.games_%')}}",
        "fontSize": 24,
        "font": "open sans"
},
        "subtitle": {
        "color": "#999999",
                "fontSize": 12,
                "font": "open sans"
        },
        "titleSubtitlePadding": 9
},
        "footer": {
        "color": "#999999",
                "fontSize": 10,
                "font": "open sans",
                "location": "bottom-left"
        },
        "size": {
        "canvasHeight": 350,
                "canvasWidth": 350,
                "pieOuterRadius": "90%"
        },
        "data": {
        "sortOrder": "value-desc",
                "content": [
                {
                "label": "{{trans('messages.wins')}}",
                        "value": {{ $wins }},
                        "color": "#047dac"
                },
                {
                "label": "{{trans('messages.losses')}}",
                        "value": {{ $losses }},
                        "color": "#000000"
                }
                ]
        },
        "labels": {
        "outer": {
        "format": "none",
                "pieDistance": 32
        },
                "inner": {
                "format": "label-percentage2",
                        "hideWhenLessThanPercentage": 3
                },
                "mainLabel": {
                "color": "#ffffff",
                        "fontSize": 11
                },
                "percentage": {
                "color": "#ffffff",
                        "decimalPlaces": 0
                },
                "value": {
                "color": "#adadad",
                        "fontSize": 11
                },
                "lines": {
                "enabled": true
                },
                "truncation": {
                "enabled": true
                }
        },
        "effects": {
        "pullOutSegmentOnClick": {
        "effect": "linear",
                "speed": 400,
                "size": 8
        },
                "highlightSegmentOnMouseover": false,
                "highlightLuminosity": 0.19
        },
        "misc": {
        "gradient": {
        "enabled": true,
                "percentage": 100
        }
        }
});</script>

<script>
    var pie = new d3pie("setpercentage", {
    "header": {
    "title": {
    "text": "{{trans('messages.sets_%')}}",
            "fontSize": 24,
            "font": "open sans"
    },
            "subtitle": {
            "color": "#999999",
                    "fontSize": 12,
                    "font": "open sans"
            },
            "titleSubtitlePadding": 9
    },
            "footer": {
            "color": "#999999",
                    "fontSize": 10,
                    "font": "open sans",
                    "location": "bottom-left"
            },
            "size": {
            "canvasHeight": 350,
                    "canvasWidth": 350,
                    "pieOuterRadius": "90%"
            },
            "data": {
            "sortOrder": "value-desc",
                    "content": [
                    {
                    "label": "{{trans('messages.sets_+')}}",
                            "value": {{ $sets_won }},
                            "color": "#047dac"
                    },
                    {
                    "label": "{{trans('messages.sets_-')}}",
                            "value": {{ $sets_lost }},
                            "color": "#000000"
                    }
                    ]
            },
            "labels": {
            "outer": {
            "format": "none",
                    "pieDistance": 32
            },
                    "inner": {
                    "format": "label-percentage2",
                            "hideWhenLessThanPercentage": 3
                    },
                    "mainLabel": {
                    "color": "#ffffff",
                            "fontSize": 11
                    },
                    "percentage": {
                    "color": "#ffffff",
                            "decimalPlaces": 0
                    },
                    "value": {
                    "color": "#adadad",
                            "fontSize": 11
                    },
                    "lines": {
                    "enabled": true
                    },
                    "truncation": {
                    "enabled": true
                    }
            },
            "effects": {
            "pullOutSegmentOnClick": {
            "effect": "linear",
                    "speed": 400,
                    "size": 8
            },
                    "highlightSegmentOnMouseover": false,
                    "highlightLuminosity": 0.19
            },
            "misc": {
            "gradient": {
            "enabled": true,
                    "percentage": 100
            }
            }
    });</script>

<script>
    var pie = new d3pie("pointpercentage", {
    "header": {
    "title": {
    "text": "{{trans('messages.points_%')}}",
            "fontSize": 24,
            "font": "open sans"
    },
            "subtitle": {
            "color": "#999999",
                    "fontSize": 12,
                    "font": "open sans"
            },
            "titleSubtitlePadding": 9
    },
            "footer": {
            "color": "#999999",
                    "fontSize": 10,
                    "font": "open sans",
                    "location": "bottom-left"
            },
            "size": {
            "canvasHeight": 350,
                    "canvasWidth": 350,
                    "pieOuterRadius": "90%"
            },
            "data": {
            "sortOrder": "value-desc",
                    "content": [
                    {
                    "label": "{{trans('messages.points_+')}}",
                            "value": {{ $points_for }},
                            "color": "#047dac"
                    },
                    {
                    "label": "{{trans('messages.points_-')}}",
                            "value": {{ $points_against }},
                            "color": "#000000"
                    }
                    ]
            },
            "labels": {
            "outer": {
            "format": "none",
                    "pieDistance": 32
            },
                    "inner": {
                    "format": "label-percentage2",
                            "hideWhenLessThanPercentage": 3
                    },
                    "mainLabel": {
                    "color": "#ffffff",
                            "fontSize": 11
                    },
                    "percentage": {
                    "color": "#ffffff",
                            "decimalPlaces": 0
                    },
                    "value": {
                    "color": "#adadad",
                            "fontSize": 11
                    },
                    "lines": {
                    "enabled": true
                    },
                    "truncation": {
                    "enabled": true
                    }
            },
            "effects": {
            "pullOutSegmentOnClick": {
            "effect": "linear",
                    "speed": 400,
                    "size": 8
            },
                    "highlightSegmentOnMouseover": false,
                    "highlightLuminosity": 0.19
            },
            "misc": {
            "gradient": {
            "enabled": true,
                    "percentage": 100
            }
            }
    });</script>

<?php 

if($ranking_trend != null){
    if(min($ranking_trend) - 10 < 1){
        $rankingMin = 1;
    }
    else{
        $rankingMin = min($ranking_trend) - 10;
    }
    $rankingMax = max($ranking_trend) + 10;
    
    
    // ticks
    $difference = $rankingMax - $rankingMin;
    $tick1 = round($rankingMin + ($difference/3));
    $tick2 = round($rankingMin + 2 * ($difference/3));

    $dateArray = array();
    $rankingTrendWithDate =  array();

    foreach($ranking_trend as $key => $value){

        $date = substr_replace($key, '-', 2, 0);
        $date = substr_replace($date, '-', 5, 0);
        //$date = new DateTime($date);

        array_push($dateArray, $date);
    }

    $rankingDateMin = min($dateArray);
    $rankingDateMax = max($dateArray);

    $dateName = trans('messages.date');
    $rankingName = trans('messages.ranking');
    $rankingTrend = trans('messages.ranking_trend');
}

?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

@if($ranking_trend != null)

    <script>
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart1);
        function drawChart1() {

        var rankingMin = {{ $rankingMin }};
        var rankingMax = {{ $rankingMax }};
        var tick1 = {{ $tick1 }};
        var tick2 = {{ $tick2 }};;
        var dateName = '{{ $dateName }}';
        var rankingName = '{{ $rankingName }}';
        
        var ticks = {{ ($rankingMax - $rankingMin) }};

        var data = new google.visualization.DataTable();
        data.addColumn('date', String(dateName));
        data.addColumn('number', String(rankingName));

        data.addRows(
            <?php 
            $i = 0;
            $string = '[';
            foreach($ranking_trend as $key => $value){
                //dd($key);
                $day = substr($key,0,2);
                $month = substr($key,2,2);

                if(intval(substr($day,0,1)) == 0){
                    $day = substr($day,1,1);
                }
                if(intval(substr($month,0,1)) == 0){
                    $month = substr($month,1,1) - 1;
                }
                else{
                    $month = $month - 1;
                }

                $month = intval($month);
                $day = intval($day);

                $string = $string.'[new Date('.substr($key,4,4).' ,'.strval($month).' ,'.strval($day).'), '.$value.'], ';

            } 
            $string = substr($string,0,strlen($string)-2);
            $string = $string.']';
            echo $string;

            ?>
        );

        var options = { 
                title: '{{ $rankingTrend }}',
                hAxis: {title: String(dateName)},
                vAxis: {title: String(rankingName), maxValue: rankingMax, minValue: rankingMin, direction: -1, ticks:[rankingMin, tick1, tick2, rankingMax]},
                curveType: 'function',
                animation:{
                    duration: 1500,
                    startup: 'true',
                    easing: 'out',
                },
        };
        var chart = new google.visualization.LineChart(document.getElementById('ranking_trend'));

        chart.draw(data, options);
        }
    </script>

@endif
    
<?php 

if ($elo_trend != null){
    $eloMin = min($elo_trend) - 10;
    $eloMax = max($elo_trend) + 10;
    $dateArray = array();
    $eloTrendWithDate =  array();

    foreach($elo_trend as $key => $value){

        $date = substr_replace($key, '-', 2, 0);
        $date = substr_replace($date, '-', 5, 0);
        //$date = new DateTime($date);

        array_push($dateArray, $date);
    }

    $eloDateMin = min($dateArray);
    $eloDateMax = max($dateArray);

    $dateName = trans('messages.date');
    $eloName = trans('messages.elo');
    $eloTrend = trans('messages.elo_trend');
}

?>

@if($elo_trend != null)

    <script>
        google.charts.load('current', {packages: ['corechart']});
        google.charts.setOnLoadCallback(drawChart2);
        function drawChart2() {

        var eloMin = {{ $eloMin }};
        var eloMax = {{ $eloMax }};
        var dateName = '{{ $dateName }}';
        var eloName = '{{ $eloName }}';

        var data = new google.visualization.DataTable();
        data.addColumn('date', String(dateName));
        data.addColumn('number', String(eloName));

        data.addRows(
            <?php 
            $i = 0;
            $string = '[';
            foreach($elo_trend as $key => $value){
                //dd($key);
                $day = substr($key,0,2);
                $month = substr($key,2,2);

                if(intval(substr($day,0,1)) == 0){
                    $day = substr($day,1,1);
                }
                if(intval(substr($month,0,1)) == 0){
                    $month = substr($month,1,1) - 1;
                }
                else{
                    $month = $month - 1;
                }

                $month = intval($month);
                $day = intval($day);

                $string = $string.'[new Date('.substr($key,4,4).' ,'.strval($month).' ,'.strval($day).'), '.$value.'], ';

            } 
            $string = substr($string,0,strlen($string)-2);
            $string = $string.']';
            echo $string;

            ?>
        );

        var options = { 
                title: '{{ $eloTrend }}',
                hAxis: {title: String(dateName)},
                vAxis: {title: String(eloName), maxValue: eloMax, mniValue: eloMin},
                curveType: 'function',
                animation:{
                    duration: 1500,
                    startup: 'true',
                    easing: 'out',
                },
        };
        var chart = new google.visualization.LineChart(document.getElementById('elo_trend'));

        chart.draw(data, options);
        }
    </script>

@endif

<script>  
    $(window).resize(function(){
        drawChart1();
        drawChart2();
      });
</script>

@endsection
