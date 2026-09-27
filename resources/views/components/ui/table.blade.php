<div {{ $attributes->class(['relative overflow-x-auto rounded-base border border-gray-200 shadow-sm dark:border-gray-700']) }}>
    <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
        {{ $slot }}
    </table>
</div>
