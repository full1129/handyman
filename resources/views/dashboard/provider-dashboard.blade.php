<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-2 text-secondary">{{ __('messages.total_name', ['name' => __('messages.booking')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ $data['dashboard']['count_total_booking'] }} </h5>
                                            <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="iq-card-icon icon-shape bg-primary text-white rounded-circle shadow">
                                            <i class="ri-calendar-check-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-2 text-secondary">{{ __('messages.total_name', ['name' => __('messages.service')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ $data['dashboard']['count_total_service'] }}</h5>
                                            <p class="mb-0 ml-3 text-success font-weight-bold"></p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="iq-card-icon icon-shape bg-warning text-white rounded-circle shadow">
                                            <i class="ri-service-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-2 text-secondary">{{ __('messages.total_name', ['name' => __('messages.handyman')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ $data['dashboard']['count_total_provider'] }}</h5>
                                            <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="iq-card-icon icon-shape bg-success text-white rounded-circle shadow">
                                            <i class="las la-user-friends"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-2 text-secondary">{{ __('messages.total_name', ['name' => __('messages.revenue')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ getPriceFormat(round($data['total_revenue'])) }}</h5>
                                            <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="iq-card-icon icon-shape bg-success text-white rounded-circle shadow">
                                            <i class="ri-secure-payment-line"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="font-weight-bold">{{__('messages.monthly_revenue')}}</h4>
                        </div>
                        <div id="monthly-revenue" class="custom-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-block card-height">
                    <div class="d-flex justify-content-between align-items-center p-3"> 
                        <h5 class="font-weight-bold">{{ __('messages.top_services') }}</h5>
                        <a href="{{ route('service.index') }}" class="float-right mr-1 btn btn-sm btn-primary">{{ __('messages.see_all') }}</a>
                    </div>
                    <div class="card-body-list">
                        <table class="table table-spacing mb-0">
                            <tbody>
                                @if(count($data['dashboard']['top_services_list']) > 0)
                                    @foreach($data['dashboard']['top_services_list'] as $services)

                                        @php
                                            $image = getSingleMedia($services->service,'service_attachment', null);
                                            $file_extention = config('constant.IMAGE_EXTENTIONS');
                                            $extention = in_array(strtolower(imageExtention($image)),$file_extention);
                                        @endphp
                                        <tr class="white-space-no-wrap  ">
                                            <td>
                                                <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                    <div class="h-avatar is-medium">
                                                        @if($extention)
                                                            <img class="avatar" alt="user-icon" src="{{ $image }}">
                                                        @else
                                                            <img class="avatar" alt="user-icon" src="{{ asset('images/file.png') }}">
                                                        @endif
                                                    </div>
                                                    <div class="data-content">
                                                        <div>
                                                            <span class="font-weight-bold">{{optional($services->service)->name ?? '-'}}</span>                           
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="pl-0 py-3">
                                                {{optional($services->service)->name ?? '-'}}
                                            </td>
                                            <td class="pl-0 py-3">
                                                {{getPriceFormat(optional($services->service)->price ?? '-')}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <div class="data-not-found">{{__('messages.not_found_entry',['name' => __('messages.service')] )}}</div>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="font-weight-bold mb-3">{{__('messages.popular_category')}}</h4>
                        <div id="popular-category" class="custom-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-block card-height">
                    <div class="d-flex justify-content-between align-items-center p-3"> 
                        <h5 class="font-weight-bold">{{__('messages.dashboard_upcomming_booking')}}</h5>
                        <a href="{{ route('booking.index') }}" class="float-right mr-1 btn btn-sm btn-primary">{{ __('messages.see_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-spacing mb-0">
                                <tbody>
                                    @if(count($data['dashboard']['upcomming_booking']) > 0)
                                        @foreach($data['dashboard']['upcomming_booking'] as $booking)
                                            <tr class="white-space-no-wrap">
                                                <td>
                                                    <h6 class="mb-0 text-uppercase text-secondary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="pr-2" width="30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{date("D, d M Y H:i:s", strtotime($booking->date))}}</h6>
                                                </td>
                                                <td class="pl-0 py-3">
                                                    {{optional($booking->service)->name ?? '-'}}
                                                </td>
                                                <td class="pl-0 py-3">
                                                    {{optional($booking->customer)->display_name ?? '-'}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                    <div class="data-not-found">{{__('messages.not_found_entry',['name' => __('messages.booking')] )}}</div>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="d-flex justify-content-between align-items-center p-3">
                        <h5 class="font-weight-bold">{{ __('messages.new_customer') }}</h4>
                        <a href="{{ route('user.index') }}" class="float-right mr-1 btn btn-sm btn-primary">{{ __('messages.see_all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                <tr class="text-secondary">
                                    <th scope="col">{{__('messages.date')}}</th>
                                    <th scope="col">{{__('messages.user')}}</th>
                                    <th scope="col">{{__('messages.email')}}</th>
                                    <th scope="col">{{__('messages.contact_number')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data['dashboard']['new_customer'] as $customer)
                                    <tr class="white-space-no-wrap">
                                        <td> {{date("d M Y", strtotime($customer->created_at))}}</td>
                                        <td>
                                            <div class="active-project-1 d-flex align-items-center mt-0 ">
                                                <div class="h-avatar is-medium">
                                                    <img class="avatar rounded-circle" alt="user-icon" src="{{ getSingleMedia($customer,'profile_image', null) }}">
                                                </div>
                                                <div class="data-content">
                                                    <div>
                                                        <span class="font-weight-bold">{{!empty($customer->display_name) ? $customer->display_name : '-'}}</span>                           
                                                    </div>
                                                </div>
                                            </div> 
                                        </td>
                                        <td>
                                            <p class="mb-0  d-flex justify-content-start align-items-center">
                                               {{!empty($customer->email) ? $customer->email : '-'}}
                                            </p>
                                        </td>
                                        <td>{{!empty($customer->contact_number) ? $customer->contact_number : '-'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-master-layout>
<script>
    var chartData = '<?php echo $data['category_chart']['chartdata']; ?>';
    var chartArray = JSON.parse(chartData);
    var chartlabel = '<?php echo $data['category_chart']['chartlabel']; ?>';
    var labelsArray = JSON.parse(chartlabel);
    if(jQuery("#popular-category").length){
          var options = {
            series:chartArray,
            chart: {
            height:330,
            type: 'donut',
            
          },
         
          labels: labelsArray,
          colors: ['#ffbb33', '#04237D', '#e60000', '#8080ff'],
          noData: {
            text: "Data not found",
            align: "center",
            verticalAlign: "Middle",
            offsetY:0,
            offsetX:0,
          },
          plotOptions: {
            pie: {
              startAngle: -90,
              endAngle: 270,
              donut:{
                size: '80%',               
                labels:{
                  show:true,
                  total:{
                    show:true,
                    color: '#BCC1C8',
                    fontSize: '18px',
                    fontFamily: 'DM Sans',
                    fontWeight: 600,
                  },
                  value: {
                    show: true,
                    fontSize: '25px',
                    fontFamily: 'DM Sans',
                    fontWeight: 700,
                    color: '#000',
                  },
                }
              }
            }
          },
          dataLabels: {
            enabled: false,
          },
          stroke: {
            lineCap: 'round'
          }, 
          grid:{
            padding: {
              bottom: 0,
          }
          },
          legends: {
            display: true,
          },
          responsive: [{
            breakpoint: 480,
            options: {
              chart: {
                height:268
              }
            }
          }]
        };
      
        var chart = new ApexCharts(document.querySelector("#popular-category"), options);
        chart.render();

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
        apexChartUpdate(chart, {
            dark: true
        })
        }

        document.addEventListener('ChangeColorMode', function (e) {
        apexChartUpdate(chart, e.detail)
        })
    }
    if(jQuery('#monthly-revenue').length){
        var options = {
        series: [{
            name: 'revenue',
            data: [ {{ implode ( ',' ,$data['revenueData'] ) }} ]
        }],
        chart: {
          height: 265,
          type: 'bar',
          toolbar:{
            show: true,
          },
          events: {
            click: function(chart, w, e) {
              // console.log(chart, w, e)
            }
          },
        },        
        plotOptions: {
            bar: {
                horizontal: false,
                s̶t̶a̶r̶t̶i̶n̶g̶S̶h̶a̶p̶e̶: 'flat',
                e̶n̶d̶i̶n̶g̶S̶h̶a̶p̶e̶: 'flat',
                borderRadius: 0,
                columnWidth: '70%',
                barHeight: '70%',
                distributed: false,
                rangeBarOverlap: true,
                rangeBarGroupRows: false,
                colors: {
                    ranges: [{
                        from: 0,
                        to: 0,
                        color: undefined
                    }],
                    backgroundBarColors: [],
                    backgroundBarOpacity: 1,
                    backgroundBarRadius: 0,
                },
                dataLabels: {
                    position: 'top',
                    maxItems: 100,
                    hideOverflowingLabels: true,
                }
            }
        },
        dataLabels: {
          enabled: false
        },
        grid: {
          xaxis: {
              lines: {
                  show: false
              }
          },
          yaxis: {
              lines: {
                  show: true
              }
          }
        },
        legend: {
          show: true
        },
        yaxis: {
          labels: {
          offsetY:0,
          minWidth: 20,
          maxWidth: 20
          },
        },
        xaxis: {
          categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'June', 
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
          ],
          labels: {
            minHeight: 22,
            maxHeight: 22,
            style: {              
              fontSize: '12px'
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#monthly-revenue"), options);
        chart.render();
    }

</script>
