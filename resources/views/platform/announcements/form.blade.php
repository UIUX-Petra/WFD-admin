@csrf
<div class="space-y-4">
    <div>
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $announcement['title'] ?? '') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="detail" class="block text-sm font-medium text-gray-700">Details</label>
        <textarea id="detail" name="detail" rows="6" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('detail', $announcement['detail'] ?? '') }}</textarea>
        @error('detail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="draft" @selected(old('status', $announcement['status'] ?? '') == 'draft')>Save as Draft</option>
            <option value="published" @selected(old('status', 'draft') == 'published')>Publish</option>
            @isset($announcement)
            <option value="archived" @selected(old('status', $announcement['status'] ?? '') == 'archived')>Archive</option>
            @endisset
        </select>
    </div>
    <div class="flex items-start space-x-3 pt-2">
        <input id="display_on_web" name="display_on_web" type="checkbox" value="1" @checked(old('display_on_web', $announcement['display_on_web'] ?? true)) class="h-4 w-4 mt-1 text-blue-600 border-gray-300 rounded">
        <div>
            <label for="display_on_web" class="font-medium text-gray-700">Display on user's website</label>
        </div>
    </div>
    <div class="flex items-start space-x-3">
        <input id="send_email" name="send_email" type="checkbox" value="1" @checked(old('send_email', false)) class="h-4 w-4 mt-1 text-blue-600 border-gray-300 rounded">
        <div>
            <label for="send_email" class="font-medium text-gray-700">Send email notification</label>
            <p class="text-gray-500 text-sm">Email will only be sent if it has "Publish" status. When edit, check it to resend</p>
        </div>
    </div>
</div>
<div class="flex justify-end space-x-3 mt-8">
    <a href="{{ route('admin.announcements.index') }}" class="px-4 py-2 font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md">Cancel</a>
    <button type="submit" class="px-4 py-2 font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
        {{ isset($announcement) ? 'Update' : 'Save' }} Announcement
    </button>
</div>
