@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-8">
            <center>
                <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
            </center>
            
            @if ($errors->any())
            <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                
            @endforeach
        </ul>
    </div>
@endif



            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                 
                    <form action="{{ route('send.notification') }}" method="POST">
                        @csrf
                     
                        <div class="form-group">
                            <label>Select Users</label>
                            <select name="user">
                            <option value="All">All Users</option>
                                @foreach($data as $row)
                                <option value="{{$row->device_token}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                       </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title">
                      <img src="" alt="">
                        </div>
                        <div class="form-group">
                            <label>Body</label>
                            <textarea class="form-control" name="body"></textarea>
                          </div>
                          <div class="form-group">
                            <label>Action Page</label>
                            <input type="text" class="form-control" name="action">
                          </div>
                          @livewire("image-upload")
     
                    </form>



                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script>

    var firebaseConfig = {
  apiKey: "AIzaSyCustYLjU5ChZEWViBIUbjds3BA9N48tFM",
  authDomain: "push-notification-65342.firebaseapp.com",
  projectId: "push-notification-65342",
  storageBucket: "push-notification-65342.appspot.com",
  messagingSenderId: "289181547379",
  appId: "1:289181547379:web:d4860b80ceb6e30475a306",
  measurementId: "G-K7VBV52PT7"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
            messaging
            .requestPermission({
                sound: false,
                announcement: true,
                  // ... other permission settings
            })
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);
                //Function is not firing on Mobile devices
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: '{{ route("save-token") }}',
                    type: 'POST',
                    data: {
                        token: token
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
     }

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
            action: payload.notification.ClickAction,
        };
        new Notification(noteTitle, noteOptions);
    });

    self.addEventListener('notificationclick', function(event) {
                event.notification.close();
                console.log('test click event');
                event.waitUntil(self.clients.openWindow('#'));
            });

</script>
@endsection
