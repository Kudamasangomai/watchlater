<x-mail::message>
# Hello {{ $user }},

Your reminder to watch this video:

## "{{ $title }}"

<x-mail::button :url="$url">
🎥 Watch on YouTube
</x-mail::button>

If you're not ready to watch the video,
you can log in change your reminder.

<x-mail::button :url="$editReminderid">
✏️ Edit Reminder
</x-mail::button>


Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
