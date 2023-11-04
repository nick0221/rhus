<x-filament-panels::page.simple>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom-style.css') }}" >
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif



    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <hr>
    <div class="imageBanner">
        <img src="{{ asset('images/banner-head.png') }}" width="80%" >
        <div class="systemTitle">
            Web-based Rural Health Unit Management System
        </div>
    </div>
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>
