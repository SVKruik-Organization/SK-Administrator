<?php

declare(strict_types=1);

namespace App\Http\Controllers\Panel\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\Settings\ModuleItemRequest;
use App\Models\Module;
use App\Models\ModuleItem;
use App\Services\ModuleItemService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class ModuleItemController extends Controller
{
    public function __construct(
        private ModuleItemService $moduleItemService
    ) {}

    public function index(Module $module): RedirectResponse
    {
        return redirect()->route('panel.settings.modules.edit', $module);
    }

    public function show(Module $module, ModuleItem $moduleItem): RedirectResponse
    {
        return redirect()->route('panel.settings.module-items.edit', [$module->id, $moduleItem->id]);
    }

    public function create(Module $module): InertiaResponse
    {
        return Inertia::render('panel/settings/moduleItems/Form', [
            'module' => $module,
        ]);
    }

    public function store(ModuleItemRequest $request, Module $module): RedirectResponse
    {
        $validated = $request->validated();
        $validated['position'] = $this->moduleItemService->getNextPosition($module);
        $validated['module_id'] = $module->id;
        ModuleItem::create($validated);

        return redirect()
            ->route('panel.settings.modules.edit', $module)
            ->with('tab', 'items');
    }

    public function edit(Module $module, ModuleItem $moduleItem): InertiaResponse
    {
        $moduleItem->load('module');

        return Inertia::render('panel/settings/moduleItems/Form', [
            'moduleItem' => $moduleItem,
            'module' => $module,
        ]);
    }

    public function update(ModuleItemRequest $request, Module $module, ModuleItem $moduleItem): RedirectResponse
    {
        $validated = $request->validated();
        $validated['module_id'] = $module->id;
        $moduleItem->update($validated);

        return redirect()
            ->route('panel.settings.modules.edit', $module)
            ->with('tab', 'items');
    }

    public function destroy(Module $module, ModuleItem $moduleItem): RedirectResponse
    {
        $moduleItem->delete();

        return redirect()->route('panel.settings.modules.edit', $module);
    }

    public function reorder(Request $request, Module $module): RedirectResponse
    {
        $moduleItems = $request->input('moduleItems');
        for ($i = 0; $i < count($moduleItems); $i++) {
            $moduleItem = ModuleItem::find($moduleItems[$i]['id']);
            $moduleItem->update(['position' => $i + 1]);
        }

        return redirect()
            ->route('panel.settings.modules.edit', $module)
            ->with('tab', 'items');
    }
}
