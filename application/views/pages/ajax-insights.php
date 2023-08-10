<div class="row">
    <br />
    <div class="col text-center">
        <!-- <h2>Overview</h2> -->
        <!-- <p>counter to count up to a target number</p> -->
    </div>
</div>

<div class="row" id="graphs">
    <div class="col-12 mb-5 chart-wrapper">
        <div id="bubblechart-container">
            <!-- bubble chart area container -->
        </div>
    </div>
    <div class="col-6 mb-5 chart-wrapper">
        <div id="barchart-container-region">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-container-latrine-coverage">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-container-sanitation-category">
            <!-- bar chart area container -->
        </div>
    </div>
    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-duration-of-water-collection">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-water-treatment">
            <!-- bar chart area container -->
        </div>
    </div>

    <div class="col-6 mb-5 chart-wrapper-">
        <div id="barchart-family-savings">
            <!-- bar chart area container -->
        </div>
    </div>
</div>

<script>
    Highcharts.chart('bubblechart-container', {
        chart: {
            type: 'packedbubble',
            height: '100%'
        },
        title: {
            text: 'Entries per District',
            align: 'left'
        },
        tooltip: {
            useHTML: true,
            pointFormat: '<b>{point.name}:</b> {point.value}HH<br><b>Monitoring: {point.followup}HH</b>'
        },
        plotOptions: {
            packedbubble: {
                minSize: '10%',
                maxSize: '150%',
                zMin: 0,
                zMax: 3000,
                layoutAlgorithm: {
                    splitSeries: false,
                    gravitationalConstant: 0.02
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}',
                    filter: {
                        property: 'y',
                        operator: '>',
                        value: 250
                    },
                    style: {
                        color: 'black',
                        textOutline: 'none',
                        fontWeight: 'normal',
                        fontSize: '30px', // Adjust the font size as needed

                    }
                }
            }
        },
        series: <?= $region_and_district ?>
                    });

    Highcharts.chart('barchart-container-region', {
        chart: {
            renderTo: 'barchart-container-region',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 15,
                depth: 50,
                viewDistance: 25
            }
        },
        title: {
            text: 'Regional Baseline Vs Monitoring for 2023',
            align: 'left'
        },
        xAxis: {
            categories: [
                'Central',
                'Eastern',
                'South Western',
                'West Nile'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        // tooltip: {
        // 	valueSuffix: ' (1000 MT)'
        // },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_region ?>
                                        },
            {
                name: 'Monitoring',
                data: <?= $followup_region ?>
                                        }
        ]
    });

    Highcharts.chart('barchart-container-latrine-coverage', {
        chart: {
            type: 'column',
        },
        title: {
            text: 'latrine Coverage',
            align: 'left'
        },
        xAxis: {
            categories: [
                'Yes',
                'No (uses field)',
                'No (shares a latrine)',
                'null'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        tooltip: {
            valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_latrine_coverage ?>
                                        },
            {
                name: 'Monitoring',
                data: <?= $followup_latrine_coverage ?>
                                        }
        ]
    });

    Highcharts.chart('barchart-container-sanitation-category', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Sanitation Categories',
            align: 'left'
        },
        xAxis: {
            categories: [
                'un-recommendable facility',
                'Shares latrine',
                'Recommendable facility',
                'Open defecation (Field)',
                'null'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        tooltip: {
            valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_sanitation_category ?>
                                        },
            {
                name: 'Monitoring',
                data: <?= $followup_sanitation_category ?>
                                        }
        ]
    });

    Highcharts.chart('barchart-duration-of-water-collection', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Duration of Water Collection',
            align: 'left'
        },
        xAxis: {
            categories: [
                '1 hour',
                '<10 minutes',
                '31-60 minutes',
                '10-30 minutes',
                'null'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        tooltip: {
            valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_water_collection ?>
                                                },
            {
                name: 'Monitoring',
                data: <?= $followup_water_collection ?>
                                                }
        ]
    });

    Highcharts.chart('barchart-water-treatment', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Drinking Water Treatment',
            align: 'left'
        },
        xAxis: {
            categories: [
                'SODIS',
                'None',
                'Filter',
                'Chlorination',
                'Boiling',
                'null'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        tooltip: {
            valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_water_treatment ?>
                                                },
            {
                name: 'Monitoring',
                data: <?= $followup_water_treatment ?>
                                                }
        ]
    });

    Highcharts.chart('barchart-family-savings', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Family Savings',
            align: 'left'
        },
        xAxis: {
            categories: [
                'Nothing',
                'More than 6$(>22,000)',
                'More than 1$- 3$ (3600 -10,900)',
                '3$ - 6$ (11,000 - 22,000)',
                '1 dollar and less (3600 & less)',
                'null'
            ],
            crosshair: true,
            accessibility: {
                description: 'Regions'
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'House Holds (1000)'
            }
        },
        tooltip: {
            valueSuffix: ' (1000 MT)'
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [
            {
                name: 'Baseline',
                data: <?= $baseline_family_savings ?>
                                                },
            {
                name: 'Monitoring',
                data: <?= $followup_family_savings ?>
                                                }
        ]
    });
    $(document).on('submit', '#form-insights', function (event) {
        event.preventDefault();
        $('#loader').show();
        $(this).ajaxSubmit({
            success: function (response) {
                $('#loader').hide();
                $('#graphs').html(response);
            }
        })

        return false
    })
</script>