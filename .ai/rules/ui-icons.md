# UI icons

- Use Lucide as the source and visual standard for interface icons.
- Prefer local inline SVGs exposed through the reusable Blade icon component (for example, `<x-icon name="search" />`) rather than external icon CDNs, remote assets, or a client-side icon library runtime.
- Add only the Lucide SVGs that are actually used by the application; do not clone the Lucide website or its full icon catalogue into the repository.
- Choose icons by semantic meaning and use the official Lucide icon name in the component API.
- Mark decorative icons as hidden from assistive technology and provide an accessible label when an icon is the only control content.
