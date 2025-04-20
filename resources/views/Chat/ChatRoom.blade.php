@extends('welcome')

@section('content')
    <div class="flex w-full h-[80vh] lg:max-w-4xl max-w-[335px] border shadow-md rounded-lg overflow-hidden">
        <!-- Sidebar (Users List) -->
        <aside class="w-1/4 bg-gray-100 p-4 overflow-y-auto border-r">
            <h2 class="text-lg font-semibold mb-4">All Users</h2>
            <ul id="listContainer">
                @forelse($users as $user)
                    <li class="relative p-2 border-b hover:bg-gray-200 cursor-pointer userlist"
                        data-user-id="{{ $user->id }}">
                        {{ $user->name }}
                        <span
                            class="unread-badge absolute right-3 top-1/2 transform -translate-y-1/2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full hidden"
                            data-user-id="{{ $user->id }}">

                        </span>
                    </li>
                @empty
                    <li class="p-2 text-gray-500">No users found</li>
                @endforelse
            </ul>
        </aside>

        <!-- Main Chat Window -->
        <div class="w-3/4 bg-white flex flex-col">
            <!-- Chat Header -->
            <div class="p-4 border-b bg-gray-200 flex items-center justify-between">

                <h2 class="text-lg font-semibold convTitle">Chat Room</h2>
                <img width="30" height="30" src="https://img.icons8.com/glyph-neue/64/unfriend-female.png"
                    alt="block-user" class="cursor-pointer hidden" id="blockUserIcon" />

                <img width="30" height="30" src="https://img.icons8.com/ios/50/search--v1.png" alt="search--v1"
                    class="cursor-pointer " id="searchIcon" />
                <input class="hidden" type="text" placeholder="Search..." id="searchInput" />
            </div>
            <!-- Chat Messages -->
            <div id="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-4">
                <!-- Messages will be loaded here -->

            </div>
            <div id="typingIndicator" class="text-sm text-gray-500 italic mt-2 hidden  self-start">
                Typing...
            </div>
            <!-- Chat Input -->
            <form class="p-4 border-t bg-gray-100 flex items-center hidden" id="chatInputContainer" data-user-id="">
                @csrf
                <input id="messageInput" type="text" class="w-full p-2 border rounded" placeholder="Type a message...">
                <button class="ml-2 bg-blue-500 text-white px-4 py-2 rounded">Send</button>
            </form>
            <div id="cantSendMessageNotice" class="text-red-500 text-sm italic mt-2 hidden">
                You can't send a message to this user.
            </div>
        </div>
    </div>

    @push('script')
        <script type="module">
            var authUserId = {{ auth()->id() }};

            var convid = sessionStorage.getItem('convid');

            $(document).ready(function() {
                getNotification();



                function getNotification() {
                    $.ajax({
                        url: "{{ route('User.UserNotification') }}",
                        method: 'GET',
                        success: function(data) {
                            renderNotifications(data.data);
                        }
                    });
                }


                if (sessionStorage.getItem('activeChat')) {
                    var userId = sessionStorage.getItem('activeChat');
                    var userName = sessionStorage.getItem('user_name');

                    openChat(userId, userName);
                }



                $('.userlist').click(function() {
                    sessionStorage.setItem('activeChat', $(this).data('user-id'));
                    var userId = $(this).data('user-id');
                    $('#chatInputContainer').data('user-id', userId);
                    var userName = $(this).text();
                    sessionStorage.setItem('user_name', userName);
                    openChat(userId, userName);
                });

                $('#searchIcon').click(function() {
                    $('[id^="message-"]').removeClass('bg-yellow-300');
                    $('#searchInput').toggleClass('hidden');
                });



                function openChat(userId, userName) {


                    $('[id^="message-"]').removeClass('bg-yellow-300');

                    $.ajax({
                        url: `getMessages/${userId}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'GET',
                        success: function(response) {


                            $('.convTitle').text(userName);
                            $('#messagesContainer').html('');
                            $('#chatInputContainer').removeClass('hidden').attr('data-user-id', userId);
                            $('#messageInput').val('');
                            $('#blockUserIcon').removeClass('hidden');
                            $('searchInput').removeClass('hidden');

                            let Convers = response.data.id;
                            let can_send = response.data.can_send_message;
                            sessionStorage.setItem('convid', Convers);
                            can_send_message(can_send);
                            subscribed(Convers);
                            console.log(response);
                            if (response.data) {

                                var messages = response.data.messages;
                                if (response.data.name) {
                                    $('.convTitle').text(response.data.name);
                                }

                                messages.forEach(function(message) {
                                    var messageClass = (message.user_id === authUserId) ?
                                        'justify-end' :
                                        'justify-start';

                                    var bubbleClass = (message.user_id === authUserId) ?
                                        'bg-blue-500 text-white' :
                                        'bg-gray-300 text-black';

                                    $('#messagesContainer').append(`
                                <div class="flex ${messageClass}">
                                    <div class="${bubbleClass} p-2 rounded-lg max-w-xs" id="message-${message.id}">${message.message}</div>
                                </div>
                            `);
                                });

                                $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
                            }
                        },
                        error: function(xhr, status, error) {
                            $('#chatInputContainer').addClass('hidden');
                            $('.convTitle').text('Chat Room');
                            $('#blockUserIcon').addClass('hidden');
                            $('#searchInput').addClass('hidden');
                            console.error(error);
                        }
                    });
                }


                function can_send_message(can_send) {
                    if (can_send) {
                        $('#chatInputContainer').removeClass('hidden');
                        $('#cantSendMessageNotice').addClass('hidden');
                        $('#blockUserIcon').removeClass('hidden');
                    } else {
                        $('#chatInputContainer').addClass('hidden');
                        $('#cantSendMessageNotice').removeClass('hidden');
                        $('#blockUserIcon').addClass('hidden');

                    }
                }

                $('#blockUserIcon').click(function() {
                    let id = $('#chatInputContainer').data('user-id');
                    console.log(id);
                    $.ajax({

                        url: `/blockUser/${id}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        method: 'POST',
                        success: function(response) {
                            console.log(response);
                            can_send_message(false);
                            notyf.success('User blocked successfully');
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });

                let debounceTimer;
                $('#searchInput').on('input', function() {
                    clearTimeout(debounceTimer);
                    var searchTerm = $(this).val();
                    var userId = $('#chatInputContainer').data('user-id');

                    debounceTimer = setTimeout(function() {
                        if (searchTerm.length >= 2) {
                            performSearch(searchTerm, userId);
                        }
                    }, 300);
                });

                function performSearch(searchTerm, userId) {
                    $.ajax({
                        url: "{{ route('message.findMessage') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            search: searchTerm,
                            user_id: userId
                        },
                        method: 'GET',
                        success: function(response) {
                            $('[id^="message-"]').removeClass('bg-yellow-300');
                            if (response.data) {
                                if (response.data.length > 0) {
                                    const data = response.data;
                                    let firstMessageElement = null;

                                    data.forEach(function(message, index) {
                                        const messageElement = $(`#message-${message.id}`);
                                        messageElement.addClass('bg-yellow-300');

                                        if (index === 0) {
                                            firstMessageElement = messageElement;
                                        }
                                    });

                                    if (firstMessageElement) {
                                        $('#messagesContainer').animate({
                                            scrollTop: firstMessageElement.position().top + $(
                                                '#messagesContainer').scrollTop()
                                        }, 500);
                                    }
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                $('#chatInputContainer').submit(function(e) {
                    e.preventDefault();
                    var messageText = $('#messageInput').val();
                    var userId = $('#chatInputContainer').data('user-id');
                    if (!messageText.trim()) return;
                    console.log(userId);
                    $.ajax({
                        url: `sendMessage/${userId}`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            message: messageText,
                        },
                        success: function(response) {
                            $('#messagesContainer').append(`
                        <div class="flex justify-end">
                            <div class="bg-blue-500 text-white p-2 rounded-lg max-w-xs">${messageText}</div>
                        </div>
                    `);
                            $('#messageInput').val('');
                            $('#messagesContainer').scrollTop($('#messagesContainer')[0]
                                .scrollHeight);
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });

                let wasTyping = false;

                $('#messageInput').on('input', function() {
                    let user_id = $('#chatInputContainer').data('user-id');
                    let message = $(this).val();

                    if (convid) {
                        if (message.length > 0 && !wasTyping) {

                            Echo.private('ChatForUser.' + convid)
                                .whisper('typing', {
                                    user: authUserId,
                                    message: "Typing..."
                                });
                            wasTyping = true;
                        } else if (message.length === 0 && wasTyping) {

                            Echo.private('ChatForUser.' + convid)
                                .whisper('stopTyping', {
                                    user: authUserId
                                });
                            wasTyping = false;
                        }
                    }


                    if($(`#messageBadge-${user_id}`).text() > 0){ 
                        $.ajax({
                        url: `/markAsRead/${user_id}`,
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(response) {
                            console.log(response);
                            $(`#messageBadge-${userId}`).text(0);
                            $(`#messageBadge-${userId}`).addClass('hidden');
                            getNotification();

                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                    
                }
                   
                });


                function subscribed(Conv) {
                    if (convid && Conv !== convid) {
                        Echo.leaveChannel(`ChatForUser.${convid}`);
                        console.log(`Unsubscribed from ChatForUser.${convid}`);
                    }

                    Echo.private(`ChatForUser.${Conv}`)
                        .listenForWhisper('typing', (e) => {
                            if (e.user !== authUserId) {
                                console.log(e.message);
                                $('#typingIndicator').removeClass('hidden');
                            }
                        })
                        .listenForWhisper('stopTyping', (e) => {
                            if (e.user !== authUserId) {
                                $('#typingIndicator').addClass('hidden');
                            }
                        })
                        .subscribed(function() {
                            console.log('Subscribed to ChatForUser.' + Conv);
                            convid = Conv;
                        })
                        .error(function(err) {
                            console.log('Error subscribing to the new channel', err);
                        });
                }

                Echo.private('blockUser.{{ auth()->user()->id }}')
                    .listen('.BlockUser', function(e) {
                        console.table(e);
                        if (e.user) {

                            if (e.id === $('#chatInputContainer').data('user-id')) {

                                can_send_message(false);

                            }
                        }
                    }).subscribed(function() {
                        console.log('Subscribed to blockUser.' + {{ auth()->user()->id }});
                    })


                Echo.private('UnBlockUser.{{ auth()->user()->id }}')
                    .listen('.UnBlockUser', function(e) {
                        if (e) {
                            if (e.id === $('#chatInputContainer').data('user-id')) {
                                can_send_message(true);
                            }
                        }
                    })


                Echo.private('App.Models.User.' + {{ auth()->user()->id }}).notification(function(e) {
                    console.log(e);
                    
                    getNotification();
                })

                Echo.private('MessageForUser.' + {{ auth()->user()->id }})
                    .listen('.MessageSent', function(e) {
                        if (e.message) {
                            playSound();
                            var message = e.message;
                            var user = e.user;
                            console.log(message);
                            if (message.user_id === authUserId) return;
                            if (message.user_id === $('#chatInputContainer').data('user-id')) {
                                console.log(message.user_id+"   "+ $('#chatInputContainer').data('user-id'));
                                var messageClass = (message.user_id === authUserId) ? 'justify-end' :
                                    'justify-start';
                                var bubbleClass = (message.user_id === authUserId) ? 'bg-blue-500 text-white' :
                                    'bg-gray-300 text-black';

                                $('#messagesContainer').append(`
                            <div class="flex ${messageClass}">
                                <div class="${bubbleClass} p-2 rounded-lg max-w-xs">${message.message}</div>
                            </div>
                        `);
                                $('#typingIndicator').addClass('hidden');
                                $('#messagesContainer').scrollTop($('#messagesContainer')[0].scrollHeight);
                            } else {
                                notyf.open({
                                    type: 'message',
                                    message: 'You have a new message from ' + message.user.name,
                                });
                            }
                        }
                    });

                function renderNotifications(notifications) {
                    const list = $('#notificationList');
                    list.empty();

                    if (notifications.length === 0) {
                        list.html(
                            '<p class="text-center text-sm text-gray-500 dark:text-gray-400">No notifications yet</p>'
                            );
                        $('#notificationBadge').addClass('hidden');
                        return;
                    }

                    notifications.forEach(noty => {
                        list.append(`
                                    <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded mb-2 cursor-pointer notify-item" data-user-id="${noty.from_user_id}">
                                        <p class="text-sm text-gray-800 dark:text-gray-100 notifyMessage" data-id="${noty.id}" data-user-id="${noty.from_user_id}" data-user-name="${noty.from_user}">
                                            You have a new notification from ${noty.from_user}
                                        </p>
                                        <span class="text-xs text-gray-400">${noty.created_at}</span>
                                    </div>
                                `);
                    });

                    let count = notifications.length;
                    if (count > 0) {
                        $('#notificationBadge').text(count).removeClass('hidden');
                    } else {
                        $('#notificationBadge').addClass('hidden');
                    }

                    let messageCount = {};
                    notifications.forEach(noty => {
                        let fromId = noty.from_user_id;
                        messageCount[fromId] = (messageCount[fromId] || 0) + 1;
                    });

                    $('#listContainer').children().each(function() {
                        let userId = $(this).data('user-id');
                        $(this).find('.message-badge').remove();

                        if (messageCount[userId]) {
                            $(this).append(`
                                        <span class="message-badge bg-red-600 text-white text-xs rounded-full px-2 ml-2" id="messageBadge-${userId}">
                                             ${messageCount[userId]}
                                        </span>
                                    `);
                        }
                    });
                }



                $('#bellIconLight, #bellIconDark').on('click', function() {
                    $('#notificationDropdown').toggleClass('hidden');


                });


                $(document).on('click', function(e) {
                    if (!$(e.target).closest('#bellIconLight, #bellIconDark, #notificationDropdown').length) {

                    }
                });


                $(document).on('click', '.notifyMessage', function() {
                    let eventid = $(this).data('id');
                    let user_id = $(this).data('user-id');
                    let userName = $(this).data('user-name');
                    openChat(user_id, userName);
                });
            });
        </script>
    @endpush
@endsection
