<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Settings\ModuleRequest;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ModuleController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('panel.settings.index', [
            'activeTab' => 'modules',
        ]);
    }

    public function show(Module $module): RedirectResponse
    {
        return redirect()->route('panel.settings.modules.edit', [
            'module' => $module,
        ]);
    }

    public function create(): InertiaResponse
    {
        return Inertia::render('panel/settings/modules/Form');
    }

    public function store(ModuleRequest $request): RedirectResponse
    {
        $module = Module::create($request->validated());

        return redirect()->route('panel.settings.modules.edit', $module);
    }

    public function edit(Module $module): InertiaResponse
    {
        $module->load('moduleItems');
        return Inertia::render('panel/settings/modules/Form', [
            'module' => $module,
        ]);
    }

    public function update(ModuleRequest $request, Module $module): RedirectResponse
    {
        $module->update($request->validated());

        return redirect()->route('panel.settings.modules.edit', $module);
    }

    public function destroy(Module $module): RedirectResponse
    {
        $module->delete();

        return redirect()->route('panel.settings.index', [
            'activeTab' => 'modules',
        ]);
    }
}
