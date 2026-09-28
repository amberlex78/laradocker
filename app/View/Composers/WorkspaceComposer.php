<?php

namespace App\View\Composers;

use Illuminate\View\View;

class WorkspaceComposer
{
    public function compose(View $view): void
    {
        $viewData = $view->getData();
        $area = $viewData['area'] ?? null;

        if ($area === null) {
            $area = request()->routeIs('admin.*')
                ? 'admin'
                : (request()->routeIs('developer.*') ? 'developer' : null);
        }

        $user = auth()->user();

        $view->with('workspace', $user?->role?->workspace($area));
    }
}
