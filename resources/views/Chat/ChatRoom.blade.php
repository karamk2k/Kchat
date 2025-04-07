@extends('welcome')

@section('content')
<div class="flex w-full h-[80vh] lg:max-w-4xl max-w-[335px] border shadow-md rounded-lg overflow-hidden">
    <!-- Sidebar (Users List) -->
    <aside class="w-1/4 bg-gray-100 p-4 overflow-y-auto border-r">
        <h2 class="text-lg font-semibold mb-4">All Users</h2>
        <ul>
            @forelse($users as $user) 
                <li class="p-2 border-b hover:bg-gray-200 cursor-pointer userlist" data-user-id="{{$user->id}}">
                    {{$user->name}}
                </li>
            @empty
                <li class="p-2 text-gray-500">No users found</li>
            @endforelse
        </ul>
    </aside>

    <!-- Main Chat Window -->
    <div class="w-3/4 bg-white flex flex-col">
        <!-- Chat Header -->
        <div class="p-4 border-b bg-gray-200 flex items-center">
            <h2 class="text-lg font-semibold convTitle">Chat Room</h2>
        </div>

        <!-- Chat Messages -->
        <div id="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-4">
            <!-- Messages will be loaded here -->
        </div>

        <!-- Chat Input -->
        <form class="p-4 border-t bg-gray-100 flex items-center hidden" id="chatInputContainer" data-user-id="">
            
                @csrf
            <input id="messageInput" type="text" class="w-full p-2 border rounded" placeholder="Type a message...">
            <button class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Send</button>
            
        </form>
    </div>
</div>
@push('script')

<script type="module">
    $(document).ready(function() {
        console.log('Chat Page Loaded');
        var authUserId = {{ auth()->id() }};
        
        $('.userlist').click(function() {
            var userId = $(this).data('user-id');
            var userName = $(this).text();
            $('.convTitle').text(userName);
            $('#messagesContainer').html(''); 
            $('#chatInputContainer').removeClass('hidden');
            $('#chatInputContainer').attr('data-user-id', userId);
            $.ajax({
                url: `getMessages/${userId}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'GET',
                success: function(response) {
                    console.log(response);

                    if (response.data) {
                        var messages = response.data.messages;

                        if (response.data.name) {
                            $('.convTitle').text(response.data.name);
                        }

                        messages.forEach(function(message) {
                            var messageClass = (message.user_id === authUserId) 
                                ? 'justify-end' 
                                : 'justify-start';

                            var bubbleClass = (message.user_id === authUserId) 
                                ? 'bg-blue-500 text-white' 
                                : 'bg-gray-300 text-black';

                            $('#messagesContainer').append(`
                                <div class="flex ${messageClass}">
                                    <div class="${bubbleClass} p-2 rounded-lg max-w-xs">${message.message}</div>
                                </div>
                            `);
                        });

                        // Scroll to bottom of messages container
                        $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        
        $('#chatInputContainer').submit(function(e) {
            e.preventDefault();
            var messageText = $('#messageInput').val();
            var userId = $('#chatInputContainer').data('user-id');
            if (!messageText.trim()) return;

           
            $.ajax({
                url: `sendMessage/${userId}`, 
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "message": messageText,
                },
                success: function(response) {
                    $('#messagesContainer').append(`
                        <div class="flex justify-end">
                            <div class="bg-blue-500 text-white p-2 rounded-lg max-w-xs">${messageText}</div>
                        </div>
                    `);
                    $('#messageInput').val(''); 
                    $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
       
    });
 let   authUserId = {{ auth()->id() }}
window.Echo.private('MessageForUser.' + authUserId)
    .listen('.MessageSent', function(e) {
        console.log("event", e);

        if (e.message) {
            playSound();
            var message = e.message;
            var user = e.user;
            if(e.message.user_id === authUserId) return;
            else if (message.user_id === $('#chatInputContainer').data('user-id')) {
            

            // Update conversation title if available
            if (user.name) {
                $('.convTitle').text(user.name);
            }

            var messageClass = (message.user_id === authUserId)
                ? 'justify-end'
                : 'justify-start';

            var bubbleClass = (message.user_id === authUserId)
                ? 'bg-blue-500 text-white'
                : 'bg-gray-300 text-black';

            // Append the message bubble to the container
            $('#messagesContainer').append(`
                <div class="flex ${messageClass}">
                    <div class="${bubbleClass} p-2 rounded-lg max-w-xs">${message.message}</div>
                </div>
            `);

            // Scroll to bottom of messages container
            $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
            }
            else {
                notyf.open({
                    type: 'message',
                    message: 'You have a new message from ' + message.user.name,
                })
            }
            
        }
    });

 
</script>
@endpush

@endsection
