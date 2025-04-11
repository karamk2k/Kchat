<header>
      
    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Blocked Users
    </h2>
       
    </header>

<section class="space-y-6" id="blockedUsersContainer">
    
</section>
    <script type="module">
     $(document).ready(function() {
    $.ajax({
        url: '/blocked_users',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'GET',
        success: function(response) {
            const container = $('#blockedUsersContainer');
            container.empty(); 
            if (response.data.length === 0) {
                container.append(`<p class="text-gray-500">You haven’t blocked anyone.</p>`);
                return;
            }
            console.log(response.data);
            response.data.forEach(user => {
                const blockItem = `
            
                    <div class="p-4 bg-white dark:bg-gray-700 rounded shadow flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <img src="${user.avatar || 'https://img.icons8.com/ios-filled/50/user.png'}" alt="${user.name}" class="w-10 h-10 rounded-full">
                            <span class="text-gray-800 dark:text-gray-100 font-medium">${user.name}</span>
                        </div>
                        <button class="unblockBtn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded" data-id="${user.user_id}">Unblock</button>
                    </div>
                `;
                container.append(blockItem);
            });

            $('.unblockBtn').click(function () {
                const userId = $(this).data('id');
                $.ajax({
                    url: `/unblockUser/${userId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        $(this).closest('div').remove();
                        if ($('#blockedUsersContainer').children().length === 0) {
                            $('#blockedUsersContainer').append(`<p class="text-gray-500">You haven’t blocked anyone.</p>`);
                        }
                        notyf.success('User unblocked successfully.');
                    }.bind(this),
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});

    </script>
