@extends('dashboard.base')

@section('content')

          <div class="container-fluid">
            <div class="fade-in">
              <div class="row">
                    <div class="col-sm-6 col-md-2">
                      <div class="card text-white bg-info">
                        <div class="card-body">
                          <div class="text-muted text-right mb-4">
                            <svg class="c-icon c-icon-2xl">
                              <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-user"></use>
                            </svg>
                          </div>
                          <div class="text-value-lg">12</div><small class="text-muted text-uppercase font-weight-bold">Students</small>
                          <div class="progress progress-white progress-xs mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-md-2">
                      <div class="card text-white bg-success">
                        <div class="card-body">
                          <div class="text-muted text-right mb-4">
                            <svg class="c-icon c-icon-2xl">
                              <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-institution"></use>
                            </svg>
                          </div>
                          <div class="text-value-lg">2</div><small class="text-muted text-uppercase font-weight-bold">Universities</small>
                          <div class="progress progress-white progress-xs mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-md-2">
                      <div class="card text-white bg-warning">
                        <div class="card-body">
                          <div class="text-muted text-right mb-4">
                            <svg class="c-icon c-icon-2xl">
                              <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-building"></use>
                            </svg>
                          </div>
                          <div class="text-value-lg">12</div><small class="text-muted text-uppercase font-weight-bold">Departments</small>
                          <div class="progress progress-white progress-xs mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-md-2">
                      <div class="card text-white bg-primary">
                        <div class="card-body">
                          <div class="text-muted text-right mb-4">
                            <svg class="c-icon c-icon-2xl">
                              <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-book"></use>
                            </svg>
                          </div>
                          <div class="text-value-lg">28</div><small class="text-muted text-uppercase font-weight-bold">Subjects</small>
                          <div class="progress progress-white progress-xs mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-md-2">
                      <div class="card text-white bg-danger">
                        <div class="card-body">
                          <div class="text-muted text-right mb-4">
                            <svg class="c-icon c-icon-2xl">
                              <use xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-speedometer"></use>
                            </svg>
                          </div>
                          <div class="text-value-lg">5</div><small class="text-muted text-uppercase font-weight-bold">Practicals</small>
                          <div class="progress progress-white progress-xs mt-3">
                            <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col-->

              </div>
              <!-- /.row-->

@endsection

@section('javascript')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endsection
