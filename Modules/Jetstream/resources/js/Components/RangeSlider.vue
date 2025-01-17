<script setup>
import Slider from '@vueform/slider';
import TextInput from "Jetstream/Components/TextInput.vue";

const props = defineProps({
    modelValue: [String, Number],
    min: Number,
    max: Number,
    step: Number,
    disabled: Boolean
})

const emit = defineEmits(['update:modelValue']);

const classes = {
    target: 'relative box-border select-none touch-none tap-highlight-transparent touch-callout-none disabled:cursor-not-allowed',
    focused: 'slider-focused',
    tooltipFocus: 'slider-tooltip-focus',
    tooltipDrag: 'slider-tooltip-drag',
    ltr: 'slider-ltr',
    rtl: 'slider-rtl',
    horizontal: 'slider-horizontal h-1.5',
    vertical: 'slider-vertical w-1.5 h-80',
    textDirectionRtl: 'slider-txt-rtl',
    textDirectionLtr: 'slider-txt-ltr',
    base: 'w-full h-full relative z-1 bg-gray-300 rounded',
    connects: 'w-full h-full relative overflow-hidden z-0 rounded',
    connect: 'absolute z-1 top-0 right-0 transform-origin-0 transform-style-flat h-full w-full bg-tf-blue-500 cursor-pointer tap:duration-300 tap:transition-transform disabled:bg-gray-400 disabled:cursor-not-allowed',
    origin: 'slider-origin absolute z-1 top-0 right-0 transform-origin-0 transform-style-flat h-full w-full h:h-0 v:-top-full txt-rtl-h:left-0 txt-rtl-h:right-auto v:w-0 tap:duration-300 tap:transition-transform',
    handle: 'absolute rounded-full bg-white border-0 shadow-slider cursor-grab focus:outline-none h:w-4 h:h-4 h:-top-1.5 h:-right-2 txt-rtl-h:-left-2 txt-rtl-h:right-auto v:w-4 v:h-4 v:-top-2 v:-right-1.25 disabled:cursor-not-allowed focus:ring focus:ring-tf-blue-500 focus:ring-opacity-30',
    handleLower: 'slider-hande-lower',
    handleUpper: 'slider-hande-upper',
    touchArea: 'h-full w-full',
    tooltip: 'hidden absolute block text-sm font-semibold whitespace-nowrap py-1 px-1.5 min-w-5 text-center text-white rounded border border-tf-blue-500 bg-tf-blue-500 transform h:-translate-x-1/2 h:left-1/2 v:-translate-y-1/2 v:top-1/2 disabled:bg-gray-400 disabled:border-gray-400 merge-h:translate-x-1/2 merge-h:left-auto merge-v:-translate-x-4 merge-v:top-auto tt-focus:hidden tt-focused:block tt-drag:hidden tt-dragging:block',
    tooltipTop: 'bottom-6 h:arrow-bottom merge-h:bottom-3.5',
    tooltipBottom: 'top-6 h:arrow-top merge-h:top-5',
    tooltipLeft: 'right-6 v:arrow-right merge-v:right-1',
    tooltipRight: 'left-6 v:arrow-left merge-v:left-7',
    tooltipHidden: 'slider-tooltip-hidden',
    active: 'slider-active shadow-slider-active cursor-grabbing',
    draggable: 'cursor-ew-resize v:cursor-ns-resize',
    tap: 'slider-state-tap',
    drag: 'slider-state-drag',
};

const updateSlider = (value) => {
    emit('update:modelValue', value);
}

const updateInput = (value) => {
    emit('update:modelValue', value);
}
</script>

<template>
    <div>
        <div class="flex flex-row items-center justify-between mb-3">
            <slot name="label" />

            <TextInput type="number"
                       class="w-20 h-8 ml-4 text-sx"
                       :value="modelValue"
                       :min="min"
                       :max="max"
                       :step="step"
                       :disabled="disabled"
                       @change="updateInput($event.target.value)"
            />
        </div>

        <Slider
                :classes="classes"
                :tooltips="false"
                class="flex flex-grow"
                :value="modelValue"
                :min="min"
                :max="max"
                :step="step"
                :disabled="disabled"
                @slide="updateSlider"
        />
    </div>
</template>
