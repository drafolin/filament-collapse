@php
    $uuid = $getUuid();
    $color = $getColor();
@endphp

<div {{$attributes}} class="grid gap-y-2"
     x-data
     x-init="
        let prefixes = [...document.querySelectorAll('[data={{$uuid()}}] .fi-input-wrp-label')]
            .map(el => ({prefix: el, content: el.innerHTML}));
        let inputs = document.querySelectorAll('[data={{$uuid()}}] .fi-input-wrp')
        let maxWidth = 0;
        let collapse = document.querySelector('[data={{$uuid()}}]')

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
                p.className = 'text-center'
                ps.push(p)
            })

            ps.forEach(p => {
                p.style.width = maxWidth + 'px'
            })

            inputs.forEach(input => {
                input.classList.remove('border-b')
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
    <div class="flex items-center justify-between gap-x-3">
        <label class="inline-flex items-center gap-x-3">
            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                {{$getLabel()}}
            </span>
        </label>
    </div>
    <div
        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->merge([
                    'data' => $getUuid(),
                    'class' => 'rounded-lg'
                ])
        }}
    >
        @foreach($getChildComponentContainers() as $container)
            @foreach($container->getComponents() as $id => $child)
                {{
                    $child
                        ->hiddenLabel()
                        ->extraAttributes([
                            'class' => '!ring-0 !border border-gray-200 dark:border-white/10'
                        ], true)
                        ->when(
                            $id !== count($container->getComponents()) - 1,
                            fn($child) => $child->extraAttributes([
                                'class' => '!border-b-0 rounded-b-none'
                            ], true),
                        )
                        ->when(
                            $id === count($container->getComponents()) - 1,
                            fn($child) => $child->extraAttributes([
                                'class' => 'rounded-t-none'
                            ], true),
                        )
                        ->placeholder($child->getLabel())
                }}
            @endforeach
        @endforeach
    </div>
</div>
