<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket No</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($tickets as $ticket)
        <tr class="hover:bg-gradient-to-r hover:from-green-50 hover:to-blue-50 transition-all">
            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->ticket_number }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->user->name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->category }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ $ticket->department }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    {{ $ticket->status === 'open' ? 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800' : '' }}
                    {{ $ticket->status === 'in_progress' ? 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800' : '' }}
                    {{ $ticket->status === 'pending' ? 'bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800' : '' }}
                    {{ $ticket->status === 'closed' ? 'bg-gradient-to-r from-green-100 to-green-200 text-green-800' : '' }}">
                    {{ ucfirst($ticket->status) }}
                    {{ $ticket->status === 'closed' ? '(Waiting for user confirmation)' : '' }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                {{ $ticket->created_at->format('d M Y H:i') }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 transition-all">View
                    Details</a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                No tickets found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>