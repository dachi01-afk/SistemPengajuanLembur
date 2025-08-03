@props(['icon', 'color', 'label', 'count'])

<div class="flex items-center p-4 bg-white rounded-lg shadow-xs">
    <div
        class="flex items-center justify-center w-12 h-12 mr-4 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-full text-xl">
        <i class="fa-solid fa-{{ $icon }}"></i>
    </div>
    <div>
        <h4 class="text-sm text-gray-600">{{ $label }}</h4>
        <p class="text-xl font-bold text-{{ $color }}-600">{{ $count }}</p>
    </div>
</div>
