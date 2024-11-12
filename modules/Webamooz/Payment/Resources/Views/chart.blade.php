<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    Highcharts.chart('container', {
        title: {
            text: 'نمودار فروش 30 روز گذشته',
            align: 'center',
            style: {
                fontFamily: 'Vazir'
            }
        },

        tooltip: {
            useHTML: true,
            style: {
                fontSize: "15px",
                fontFamily: "Vazir",
                direction: "rtl"
            },
            formatter: function () {
                return (this.x ? "تاریخ: " + this.x + "<br/>" : "") + "مبلغ: " + this.y  //this.x -> Date + this.y -> Price
            }
        },

        xAxis: {
            categories: [@foreach ($dates as $date => $value) "{{ \App\Helper\Generate::getDateMiadiToJalili($date) }}", @endforeach]
        },
        yAxis: {
            title: {
                text: 'مبلغ'
            },
            labels: {
                formatter: function () {
                    return this.value + "تومان"
                }
            }
        },
        plotOptions: {
            series: {
                borderRadius: '25%'
            }
        },
        series: [
            {
                type: 'column',
                name: 'درصد سایت',
                data: [@foreach ($dates as $date => $value) @if($day = $summery->where('date' , $date)->first())
                    {{ $day->totalSiteShare }}, @else 0, @endif @endforeach],
                color: "deepskyblue"
            },
            {
                type: 'column',
                name: 'تراکنش موفق',
                //key(Date) => value(0)
                data: [@foreach ($dates as $date => $value) @if($day = $summery->where('date' , $date)->first())
                    {{ $day->totalAmount }}, @else 0, @endif @endforeach],
                color: "#4ad186"
            },
            {
                type: 'column',
                name: 'درصد مدرس',
                data: [@foreach ($dates as $date => $value) @if($day = $summery->where('date' , $date)->first())
                    {{ $day->totalSellerShare }}, @else 0, @endif @endforeach],
                color: "darkorange"
            },

                {{--
                {
                    type: 'column',
                    name: 'تراکنش ناموفق',
                    data: [@foreach ($dates as $date => $value) @if($day = $summery->where('date' , $date)->first())
                        {{ $day->totalAmount }}, @else 0, @endif @endforeach]
                },
                --}}

            {
                type: 'line',
                step: 'center',
                name: 'فروش',
                data: [@foreach ($dates as $date => $value) @if($day = $summery->where('date' , $date)->first())
                    {{ $day->totalAmount }}, @else 0, @endif @endforeach],
                marker: {
                    lineWidth: 2,
                    lineColor: "green",
                    fillColor: 'white'
                },
            }, {
                type: 'pie',
                name: 'نسبت',
                data: [
                    {
                        name: 'درصد سایت',
                        y: {{ $last3DaysBenefit }},
                        color: "deepskyblue"
                    }, {
                        name: 'درصد مدرس',
                        y: {{ $last3DaysSellerShare }},
                        color: "darkorange"
                        // color: Highcharts.getOptions().colors[2] // 2021 color
                    }],
                center: [75, 65],
                size: 100,
                innerSize: '70%',
                showInLegend: false,
                dataLabels: {
                    enabled: false
                }
            }
        ]

    });
</script>

