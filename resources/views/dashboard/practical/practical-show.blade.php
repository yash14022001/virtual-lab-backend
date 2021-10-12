@extends('dashboard.base')

@section('content')
@if(session('message'))
  <div class="flash-message" id="flash-message" data-expires="5000" style="padding: 2rem;">
      <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{session('message')}}</p>
  </div> <!-- end .flash-message -->    
  @endif
        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <h4>Title:</h4>
                        <p> {{ $practical->title }}</p>
                        <h4>Youtube Link:</h4> 
                        <p>{{ $practical->youtube_link ?? "No Youtube link provided" }}</p>
                        <h4>Subject:</h4> 
                        <p>{{ $practical->subject->name }}</p>
                        <h4>Department:</h4> 
                        <p>{{ $practical->subject->department->name }}</p>
                        <h4>University:</h4> 
                        <p>{{ $practical->subject->department->university->name }}</p>
                        <h4>Inputs:</h4> 
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                              <tr>
                                <th>Sr. no.</th>
                                <th>Input Name</th>
                                <th>Input Type</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $index = 1; ?>
                              @foreach($practical->inputs as $input)
                                <tr>
                                  <td>{{ $index++ }}</td>
                                  <td><strong>{{ $input->name }}</strong></td>
                                  <td>{{ $input->input->name }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>

                          <h4>Outputs:</h4> 
                        <table class="table table-responsive-sm table-striped">
                            <thead>
                              <tr>
                                <th>Sr. no.</th>
                                <th>Output Name</th>
                                <th>Output Type</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php $index = 1; ?>
                              @foreach($practical->outputs as $output)
                                <tr>
                                  <td>{{ $index++ }}</td>
                                  <td><strong>{{ $output->name }}</strong></td>
                                  <td>{{ $output->output->name }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        <a href="{{ route('practical.index') }}" class="btn btn-block btn-primary">{{ __('Return') }}</a>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


@section('javascript')

@endsection