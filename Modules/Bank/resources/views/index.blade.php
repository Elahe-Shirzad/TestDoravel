<x-default-layout>
    {!! bladeLayout()->table()->render() !!}
    @push('modals')
{{--        @include('ModuleName::BladeName')--}}
        @include('bank::create')
    @endpush
</x-default-layout>
