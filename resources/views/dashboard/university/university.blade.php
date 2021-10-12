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
                    <div class="card-header">
                      <i class="fa fa-align-justify"></i>{{ __('University') }}</div>
                    <div class="card-body">
                        <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4">
                            <form method="POST" action="{{ route('university.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label>University name : </label>
                                    <input class="form-control" type="text" placeholder="{{ __('University') }}" name="name" required autofocus>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                                <div class="row"> 
                                    <button class="btn btn-block btn-success" type="submit">{{ __('Add University') }}</button>
                                </div>
                            </form>
                        </div>
                        
                        <br>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th style="width: 100px;">Sr. no. </th>
                            <th>University Name</th>
                            <th style="width: 10rem;">Edit University</th>
                            <th style="width: 10rem;">Delete University</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $index = $universities->perPage() * ($universities->currentPage() - 1) + 1; ?>
                          @foreach($universities as $university)
                            <tr>
                              <td>{{$index++}}</td>
                              <td><strong>{{ $university->name }}</strong></td>
                              <td><button class="btn btn-block btn-success" data-name="{{$university->name}}" id="edit-university" data-attr="{{ route('university.update', $university->id) }}" type="button" data-toggle="modal" data-target="#edit-modal">Edit University</button></td>
                              <td>
                                <form action="{{ route('university.destroy', $university->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $universities->links() }}
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Edit University</h4>
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                      <form id="edit-form" method="POST">
                          @method('PUT')
                          @csrf
                          <label>University name : </label>
                          <input class="form-control" type="text" placeholder="{{ __('University') }}" id="modal-university-name" name="name" required autofocus>
                          @if($errors->has('name'))
                              <div class="text-danger">{{ $errors->first('name') }}</div>
                          @endif
                          <br/>
                          <button class="btn btn-primary" type="submit">Save changes</button>
                      </form>
                  </div>
                  <div class="modal-footer">
                      <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                  </div>
              </div>
            </div>
        </div>
      
@endsection

@section('javascript')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    setTimeout(function() {
        $('#flash-message').fadeOut('fast');
    }, 3000);



    $(document).on('click', '#edit-university', function(event) {
        event.preventDefault();
        console.log("Called");
        let href = $(this).attr('data-attr');
        $("#modal-university-name").val($(this).attr('data-name'));
        $("#edit-form").attr('action', href);
    });
 
</script>

@endsection