@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'glass-input']) !!}>

<style>
    .glass-input {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
        padding: 0.8rem 1rem !important;
        color: white !important;
        width: 100% !important;
        transition: 0.3s;
    }
    .glass-input:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        outline: none;
    }
</style>
