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
                      <i class="fa fa-align-justify"></i>{{ __('Departments') }}</div>
                    <div class="card-body">
                        {{-- <div class="col-sm-12 col-md-6 col-lg-5 col-xl-4">
                            <form method="POST" action="{{ route('department.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label>University : </label>
                                    <select class="form-control" name="university">
                                      @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                      @endforeach
                                    </select>
                                </div>

                                <div class="form-group row">
                                    <label>Department name : </label>
                                    <input class="form-control" type="text" placeholder="{{ __('Department') }}" name="name" required autofocus>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                                  --}}

                                <div class="row">
                                  <button class="btn btn-block btn-success" id="add-student" type="button" data-toggle="modal" data-target="#add-modal">Add Student</button>
                                </div>
                            </form>
                        </div>

                        <br>
                        <table class="table table-responsive-sm table-striped">
                        <thead>
                          <tr>
                            <th style="width: 100px;">Sr. no. </th>
                            <th>Student's Name</th>
                            <th>Student's Email</th>
                            <th>Student's Phone</th>
                            <th>Student's University</th>
                            <th>Student Is Verified</th>
                            {{-- <th style="width: 10rem;">Edit Student</th> --}}
                            <th style="width: 10rem;">Delete Student</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php $index = $students->perPage() * ($students->currentPage() - 1) + 1; ?>
                          @foreach($students as $student)
                            <tr>
                              <td>{{$index++}}</td>
                              <td><strong>{{ $student->name }}</strong></td>
                              <td><strong>{{ $student->email }}</strong></td>
                              <td><strong>{{ $student->number }}</strong></td>
                              <td><strong>{{ $student->university->name }}</strong></td>
                              <td><strong>{{ $student->is_verified ? "Yes" : "No" }}</strong></td>
                              {{-- <td><button class="btn btn-block btn-success" data-name="{{$student->name}}" data-email="{{$student->email}}" data-number="{{$student->number}}" id="edit-student" data-attr="{{ route('students.update', $student->id) }}" data-university="{{ $student->university->name }}" type="button" data-toggle="modal" data-target="#edit-modal">Edit Student</button></td>
                              <td> --}}
                                <form action="{{ route('students.destroy', $student->id ) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-block btn-danger">Delete</button>
                                </form>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                      {{ $students->links() }}
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
                        <h4 class="modal-title">Edit Student</h4>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-form" method="POST">
                            @method('PUT')
                            @csrf
                                <label>Student's Name : </label>
                                <input class="form-control" type="text" placeholder="{{ __('Full Name') }}" id="modal-student-name" name="name" required autofocus>
                                @if($errors->has('name'))
                                    <div class="text-danger">{{ $errors->first('name') }}</div>
                                @endif
                                <label>Student's Email : </label>
                                <input class="form-control" type="text" placeholder="{{ __('Full Name') }}" id="modal-student-email" name="email" required>
                                @if($errors->has('email'))
                                    <div class="text-danger">{{ $errors->first('email') }}</div>
                                @endif
                                <label>Student's Phone Number : </label>
                                <input class="form-control" type="text" placeholder="{{ __('Full Name') }}" id="modal-student-number" name="number" maxlength="10" required>
                                @if($errors->has('number'))
                                    <div class="text-danger">{{ $errors->first('number') }}</div>
                                @endif
                                <label>University : </label>
                                <select class="form-control" id="university-select" name="university">
                                    @foreach ($universities as $university)
                                        <option value="{{$university->id}}">{{$university->name}}</option>
                                    @endforeach
                                </select>


                                <br>
                                <button class="btn btn-primary" type="submit">Save changes</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Add Student</h4>
                      <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body">
                      <form id="insert-form" action="{{ route('students.store') }}" method="POST">
                          @csrf
                              <label>Student's Name : </label>
                              <input class="form-control" type="text" placeholder="{{ __('Full Name') }}" name="name" autofocus>
                              @if($errors->has('name'))
                                  <div class="text-danger">{{ $errors->first('name') }}</div>
                              @endif
                              <label>Student's Email : </label>
                              <input class="form-control" type="text" placeholder="{{ __('Email') }}" name="email">
                              @if($errors->has('email'))
                                  <div class="text-danger">{{ $errors->first('email') }}</div>
                              @endif
                              <label>Student's Phone Number : </label>
                              <input class="form-control" type="text" placeholder="{{ __('Phone Number') }}" name="number" maxlength="10">
                              @if($errors->has('number'))
                                  <div class="text-danger">{{ $errors->first('number') }}</div>
                              @endif
                              <label>University : </label>
                              <select class="form-control" name="university">
                                  @foreach ($universities as $university)
                                      <option value="{{$university->id}}">{{$university->name}}</option>
                                  @endforeach
                              </select>


                              <br>
                              <button class="btn btn-primary" type="submit">Add Student</button>
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



    $(document).on('click', '#edit-student', function(event) {
        event.preventDefault();
        console.log("Called");
        let href = $(this).attr('data-attr');
        $("#modal-student-name").val($(this).attr('data-name'));
        $("#modal-student-email").val($(this).attr('data-email'));
        $("#modal-student-number").val($(this).attr('data-number'));
        $("#edit-form").attr('action', href);

        let selectObj = document.getElementById("university-select");
        let valueToSet = $(this).attr('data-university');
        for (var i = 0; i < selectObj.options.length; i++) {
          if (selectObj.options[i].text== valueToSet) {
              selectObj.options[i].selected = true;
              return;
          }
        }
    });

</script>

@endsection
