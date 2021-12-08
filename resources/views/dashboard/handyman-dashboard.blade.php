<x-master-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="mb-2 text-secondary">{{ __('messages.total_name', ['name' => __('messages.booking')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ !empty($data['dashboard']['count_total_booking']) ? $data['dashboard']['count_total_booking']: 0 }} </h5>
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
                                        <p class="mb-2 text-secondary">{{ __('messages.pending', ['name' => __('messages.booking')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ !empty($data['dashboard']['count_handyman_pending_booking']) ? $data['dashboard']['count_handyman_pending_booking'] : 0 }}</h5>
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
                                        <p class="mb-2 text-secondary">{{ __('messages.complete', ['name' => __('messages.booking')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ !empty($data['dashboard']['count_handyman_complete_booking']) ? $data['dashboard']['count_handyman_complete_booking'] : 0 }}</h5>
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
                                        <p class="mb-2 text-secondary">{{ __('messages.cancelled', ['name' => __('messages.booking')]) }}</p>
                                        <div class="d-flex flex-wrap justify-content-start align-items-center">
                                            <h5 class="mb-0 font-weight-bold">{{ !empty($data['dashboard']['count_handyman_cancelled_booking']) ? $data['dashboard']['count_handyman_cancelled_booking'] : 0 }}</h5>
                                            <p class="mb-0 ml-3 text-danger font-weight-bold"></p>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <div class="iq-card-icon icon-shape bg-danger text-white rounded-circle shadow">
                                            <i class="las la-user-friends"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-body">
                  <div id='calendar'></div>
                </div>
              </div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    @section('bottom_script')
    <script>
    if (jQuery('#calendar').length) {
      document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {

      plugins: [ 'dayGrid','timeGrid', 'dayGrid', 'list','interaction','bootstrap' ],
      defaultView: 'dayGridMonth',
      displayEventTime: true,
      themeSystem: 'bootstrap',
      header: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
          clear: ''
      },
      height : 600,
      selectable: true,
      selectHelper: true,
      editable: true,
      eventLimit: false,
      showNonCurrentDates : false,
      droppable: false,
      editable:false,
      eventSources:[{
        events: function (info, successCallback, failureCallback) {
            $.ajax({
                url:  "{{ route('home') }}",
                dataType: 'JSON',
                data: {
                    start: info['startStr'],
                    end: info['endStr'],
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    successCallback(response);
                    return response;
                },
                failure: function(data){
                    failureCallback(data);
                }
            });
        },
        color  : "rgb(19, 193, 240)",
        textColor : "#fff",
        eventDataTransform: function(eventData) {
          return {
              id: eventData.id,
              title: (eventData.service != null && eventData.service != '') ? eventData.service.name : '-' ,
              start: eventData.date,
          };
        },
      }],
      eventRender: function (event, element, view) {
          if (event.allDay === 'true') {
              event.allDay = true;
          } else {
              event.allDay = false;
          }
      },
      eventDrop: function(info) {},
      eventClick:  function(info) {
        var id = info.event.id;
        var url = "{{ URL::to('booking') }}/"+id;
        window.location.replace(url);
      },
    });
    calendar.render();
    });
    }
</script>
    @endsection
</x-master-layout>

