@php
    use Filament\Support\Facades\FilamentAsset;
    use function Filament\Support\prepare_inherited_attributes;
    $uuid = $getUuid();
@endphp

<x-dynamic-component
    :label="$getLabel()"
    :id="$getId()"
    :component="$getFieldWrapperView()"
>
    <div
        {{$attributes}}
        x-data
        x-init="
        let prefixes = [...document.querySelectorAll('[data={{$uuid}}] .fi-input-wrp-label')]
            .map(el => ({prefix: el, content: el.innerHTML}));
        let inputs = document.querySelectorAll('[data={{$uuid}}] .fi-input-wrp')
        let maxWidth = 0;
        let collapse = document.querySelector('[data={{$uuid}}]')

        prefixes.forEach(({prefix}) => {
            let el = prefix.cloneNode(true)
            el.style.display = 'inline-block'
            document.body.appendChild(el)
            maxWidth = Math.max(maxWidth, el.clientWidth)
            el.remove()
        })

        const updateFields = (() => {
            let ps = []
            prefixes.forEach(({prefix, content}) => {
                prefix.innerHTML = ''

                let p = document.createElement('p')
                p.innerHTML = content
                prefix.appendChild(p)
                p.style.textAlign = 'center'
                ps.push(p)
            })

            ps.forEach(p => {
                p.style.width = maxWidth + 'px'
            })
        })

        updateFields()

        const observer = new MutationObserver((mutationsList, observer) => {
        observer.disconnect()
        updateFields()
        observer.observe(collapse, {childList: true})
        })

        observer.observe(collapse, {childList: true})
    ">
        <x-filament::input.wrapper
            :data="$uuid"
            class="filament-collapse__wrapper"
            :attributes="
            prepare_inherited_attributes($getExtraAttributeBag())
        "
        >
            @foreach($getChildComponentContainers() as $container)
                @foreach($container->getComponents() as $id => $child)
                    {{
                        $child
                            ->hiddenLabel(count($container->getComponents()) - 1)
                            ->extraAttributes([
                                'class' => 'filament-collapse__collapse-item'
                            ], true)
                        ->when(
                            $id === 0,
                            fn($child) => $child->extraAttributes([
                                'class' => 'filament-collapse__collapse-item-first'
                            ], true),
                        )
                        ->when(
                            $id === count($container->getComponents()) - 1,
                            fn($child) => $child->extraAttributes([
                                'class' => 'filament-collapse__collapse-item-last'
                            ], true),
                        )
                            ->placeholder($child->getLabel())
                    }}
                @endforeach
            @endforeach
        </x-filament::input.wrapper>
    </div>
</x-dynamic-component>
