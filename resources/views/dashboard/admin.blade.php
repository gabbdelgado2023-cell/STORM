<x-app-layout>
    <div class="p-8">
        <h1 class="text-3xl font-bold text-red-600">🛠️ Admin Dashboard</h1>    
          
        <p class="mt-4 text-gray-700">Welcome, Admin! Manage users, roles, and system settings.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-xl font-semibold">User Management</h2>
                <p class="mt-2 text-gray-500">Add, edit, or remove users (students, officers, deans).</p>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-xl font-semibold">System Settings</h2>
                <p class="mt-2 text-gray-500">Control system-wide preferences.</p>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-xl font-semibold">Reports</h2>
                <p class="mt-2 text-gray-500">Generate activity and membership reports.</p>
            </div>
        </div>
        
    </div>
        

</x-app-layout>
