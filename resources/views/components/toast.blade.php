@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session($type))
        @php
            $classes = [
                'success' => 'bg-green-100 border border-green-400 text-green-700',
                'error' => 'bg-red-100 border border-red-400 text-red-700',
                'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-700',
                'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
            ];
            $iconColor = [
                'success' => 'text-green-500',
                'error' => 'text-red-500',
                'warning' => 'text-yellow-500',
                'info' => 'text-blue-500',
            ];
        @endphp

        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
            class="fixed top-10 right-5 z-50 w-auto max-w-xs text-sm 
                px-4 py-3 rounded-lg shadow-lg transition ease-in-out duration-300 {{ $classes[$type] }}"
            role="alert">
            <strong class="font-bold capitalize">{{ $type }}!</strong>
            <span class="block sm:inline">{{ session($type) }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                {{-- <svg @click="show = false" class="fill-current h-6 w-6 {{ $iconColor[$type] }}" role="button"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Tutup</title>
                    <path
                        d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z" />
                </svg> --}}
            </span>
        </div>
    @endif
@endforeach
