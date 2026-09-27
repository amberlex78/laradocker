<x-layouts.site title="Flowbite component demo — Laravel + Flowbite">
    <x-site.component-page-header
        eyebrow="Laravel + Flowbite · Live demo"
        title="Flowbite component demo"
        description="Один екран із reusable Blade-компонентами Flowbite та інтерактивними Alpine.js-елементами."
        back-route="components.index"
        back-label="Back to component library"
    />

    <main x-data class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-end gap-3">
            <x-ui.button variant="secondary" size="sm" @click="$refs.table.scrollIntoView({ behavior: 'smooth' })">
                До таблиці
            </x-ui.button>
            <x-ui.button size="sm" @click="$dispatch('open-modal', 'demo-modal')">
                Відкрити modal
            </x-ui.button>
        </div>
            <x-ui.alert variant="success" title="Success alert" data-component="alert">
                Flowbite підключено через Vite, а цей alert — reusable Blade-компонент із підтримкою атрибутів.
            </x-ui.alert>

            <section class="grid gap-6 lg:grid-cols-2" aria-label="Buttons and badges">
                <x-ui.card title="Buttons" description="Варіанти та розміри передаються через props." data-component="button">
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button>Primary</x-ui.button>
                        <x-ui.button variant="secondary">Secondary</x-ui.button>
                        <x-ui.button variant="success" size="sm">Success</x-ui.button>
                        <x-ui.button variant="danger" size="sm">Danger</x-ui.button>
                        <x-ui.button variant="ghost" size="sm">Ghost</x-ui.button>
                    </div>
                </x-ui.card>

                <x-ui.card title="Badges" description="Невеликі статуси для списків, таблиць і карток." data-component="badge">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge>Draft</x-ui.badge>
                        <x-ui.badge variant="blue">In review</x-ui.badge>
                        <x-ui.badge variant="green">Published</x-ui.badge>
                        <x-ui.badge variant="yellow">Pending</x-ui.badge>
                        <x-ui.badge variant="red">Archived</x-ui.badge>
                    </div>
                </x-ui.card>
            </section>

            <section class="grid gap-6 lg:grid-cols-2" aria-label="Form components">
                <x-ui.card title="Form controls" description="Компоненти форм передають стандартні HTML-атрибути вниз." data-component="form">
                    <form class="grid gap-5 sm:grid-cols-2" action="#" method="get">
                        <div class="sm:col-span-2">
                            <x-ui.input
                                id="demo-email"
                                name="demo-email"
                                type="email"
                                label="Email"
                                placeholder="you@example.com"
                                hint="Звичайний input із hint-текстом."
                            />
                        </div>

                        <x-ui.select name="demo-role" label="Role">
                            <option selected>Choose a role</option>
                            <option value="developer">Developer</option>
                            <option value="designer">Designer</option>
                            <option value="manager">Manager</option>
                        </x-ui.select>

                        <x-ui.input name="demo-project" label="Project name" value="Flowbite demo" />

                        <div class="sm:col-span-2">
                            <x-ui.textarea name="demo-message" label="Message" hint="Textarea підтримує slot як початкове значення.">Короткий приклад повідомлення для команди.</x-ui.textarea>
                        </div>

                        <div class="flex flex-wrap gap-3 sm:col-span-2">
                            <x-ui.button type="submit">Submit form</x-ui.button>
                            <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                        </div>
                    </form>
                </x-ui.card>

                <x-ui.card title="Card composition" description="Card може містити довільний Blade-контент і вкладені компоненти." data-component="card">
                    <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700/50">
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Team workspace</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Reusable content inside a card</p>
                            </div>
                            <x-ui.badge variant="green">Active</x-ui.badge>
                        </div>
                        <div class="h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-600">
                            <div class="h-2.5 w-3/4 rounded-full bg-blue-600" style="width: 75%"></div>
                        </div>
                        <p class="mt-2 text-right text-xs text-gray-500 dark:text-gray-400">75% complete</p>
                    </div>
                </x-ui.card>
            </section>

            <section class="grid gap-6 lg:grid-cols-2" aria-label="Interactive components">
                <x-ui.card title="Dropdown" description="Стан меню та закриття по кліку назовні обробляє Alpine.js." data-component="dropdown">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-ui.dropdown id="demo-dropdown" label="Open menu">
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profile</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Settings</a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Sign out</a>
                            </li>
                        </x-ui.dropdown>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Натисни кнопку, щоб відкрити меню.</p>
                    </div>
                </x-ui.card>

                <x-ui.card title="Modal" description="Компонент відкривається та закривається через Alpine.js-події." data-component="modal">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-ui.button @click="$dispatch('open-modal', 'demo-modal')">
                            Open demo modal
                        </x-ui.button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Працює без окремого page-specific JavaScript.</p>
                    </div>
                </x-ui.card>

                <x-ui.card title="Popup modal" description="Підтвердження потенційно небезпечної дії з окремим footer для кнопок." data-component="popup-modal">
                    <div class="flex flex-wrap items-center gap-4">
                        <x-ui.button variant="danger" @click="$dispatch('open-modal', 'popup-modal')">
                            Open popup modal
                        </x-ui.button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Демонстраційне підтвердження без реального видалення.</p>
                    </div>
                </x-ui.card>
            </section>

            <section x-ref="table" aria-label="Table components">
                <x-ui.card title="Table components" description="Варіанти Flowbite складаються з однієї reusable таблиці та дочірніх head, row і cell-компонентів." data-component="table">
                    <div class="grid gap-6 xl:grid-cols-2">
                        <div class="space-y-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Default table</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Responsive table with a caption and semantic cells.</p>
                            </div>

                            <x-ui.table caption="Projects in the current workspace">
                                <x-ui.table.head>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Project</x-ui.table.cell>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Status</x-ui.table.cell>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Members</x-ui.table.cell>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Updated</x-ui.table.cell>
                                </x-ui.table.head>
                                <tbody>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Flowbite integration</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="green">Active</x-ui.badge></x-ui.table.cell>
                                        <x-ui.table.cell>8</x-ui.table.cell>
                                        <x-ui.table.cell>Today</x-ui.table.cell>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Design system</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="blue">In review</x-ui.badge></x-ui.table.cell>
                                        <x-ui.table.cell>4</x-ui.table.cell>
                                        <x-ui.table.cell>Yesterday</x-ui.table.cell>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Marketing site</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="yellow">Pending</x-ui.badge></x-ui.table.cell>
                                        <x-ui.table.cell>3</x-ui.table.cell>
                                        <x-ui.table.cell>2 days ago</x-ui.table.cell>
                                    </x-ui.table.row>
                                </tbody>
                            </x-ui.table>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Striped and hoverable</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">A borderless variant for denser data presentations.</p>
                            </div>

                            <x-ui.table variant="borderless" :striped="true" :hoverable="true">
                                <x-ui.table.head>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Team</x-ui.table.cell>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Members</x-ui.table.cell>
                                    <x-ui.table.cell as="th" variant="header" scope="col">Status</x-ui.table.cell>
                                </x-ui.table.head>
                                <tbody>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Design</x-ui.table.cell>
                                        <x-ui.table.cell>12</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="green">Active</x-ui.badge></x-ui.table.cell>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Engineering</x-ui.table.cell>
                                        <x-ui.table.cell>24</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="blue">Review</x-ui.badge></x-ui.table.cell>
                                    </x-ui.table.row>
                                    <x-ui.table.row>
                                        <x-ui.table.cell as="th" scope="row">Marketing</x-ui.table.cell>
                                        <x-ui.table.cell>7</x-ui.table.cell>
                                        <x-ui.table.cell><x-ui.badge variant="yellow">Pending</x-ui.badge></x-ui.table.cell>
                                    </x-ui.table.row>
                                </tbody>
                            </x-ui.table>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Products with pagination</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">A complete table composition with selection, actions and an optional Flowbite-style footer.</p>
                        </div>

                        <x-ui.table variant="shadow">
                            <x-ui.table.head>
                                <x-ui.table.cell as="th" variant="header" scope="col" class="w-4">
                                    <x-ui.table.checkbox id="products-select-all" label="Select all products" />
                                </x-ui.table.cell>
                                <x-ui.table.cell as="th" variant="header" scope="col">Product name</x-ui.table.cell>
                                <x-ui.table.cell as="th" variant="header" scope="col">Color</x-ui.table.cell>
                                <x-ui.table.cell as="th" variant="header" scope="col">Category</x-ui.table.cell>
                                <x-ui.table.cell as="th" variant="header" scope="col">Price</x-ui.table.cell>
                                <x-ui.table.cell as="th" variant="header" scope="col">Action</x-ui.table.cell>
                            </x-ui.table.head>
                            <tbody>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-1" label="Select Apple MacBook Pro 17 inch" value="1" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Apple MacBook Pro 17&quot;</x-ui.table.cell>
                                    <x-ui.table.cell>Silver</x-ui.table.cell>
                                    <x-ui.table.cell>Laptop</x-ui.table.cell>
                                    <x-ui.table.cell>$2999</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-2" label="Select Microsoft Surface Pro" value="2" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Microsoft Surface Pro</x-ui.table.cell>
                                    <x-ui.table.cell>White</x-ui.table.cell>
                                    <x-ui.table.cell>Laptop PC</x-ui.table.cell>
                                    <x-ui.table.cell>$1999</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-3" label="Select Magic Mouse 2" value="3" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Magic Mouse 2</x-ui.table.cell>
                                    <x-ui.table.cell>Black</x-ui.table.cell>
                                    <x-ui.table.cell>Accessories</x-ui.table.cell>
                                    <x-ui.table.cell>$99</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-4" label="Select Apple Watch" value="4" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Apple Watch</x-ui.table.cell>
                                    <x-ui.table.cell>Black</x-ui.table.cell>
                                    <x-ui.table.cell>Watches</x-ui.table.cell>
                                    <x-ui.table.cell>$199</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-5" label="Select Apple iMac" value="5" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Apple iMac</x-ui.table.cell>
                                    <x-ui.table.cell>Silver</x-ui.table.cell>
                                    <x-ui.table.cell>PC</x-ui.table.cell>
                                    <x-ui.table.cell>$2999</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-6" label="Select Apple AirPods" value="6" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Apple AirPods</x-ui.table.cell>
                                    <x-ui.table.cell>White</x-ui.table.cell>
                                    <x-ui.table.cell>Accessories</x-ui.table.cell>
                                    <x-ui.table.cell>$399</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-7" label="Select iPad Pro" value="7" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">iPad Pro</x-ui.table.cell>
                                    <x-ui.table.cell>Gold</x-ui.table.cell>
                                    <x-ui.table.cell>Tablet</x-ui.table.cell>
                                    <x-ui.table.cell>$699</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-8" label="Select Magic Keyboard" value="8" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Magic Keyboard</x-ui.table.cell>
                                    <x-ui.table.cell>Black</x-ui.table.cell>
                                    <x-ui.table.cell>Accessories</x-ui.table.cell>
                                    <x-ui.table.cell>$99</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-9" label="Select Smart Folio iPad Air" value="9" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">Smart Folio iPad Air</x-ui.table.cell>
                                    <x-ui.table.cell>Blue</x-ui.table.cell>
                                    <x-ui.table.cell>Accessories</x-ui.table.cell>
                                    <x-ui.table.cell>$79</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                                <x-ui.table.row>
                                    <x-ui.table.cell class="w-4"><x-ui.table.checkbox id="product-10" label="Select AirTag" value="10" /></x-ui.table.cell>
                                    <x-ui.table.cell as="th" scope="row">AirTag</x-ui.table.cell>
                                    <x-ui.table.cell>Silver</x-ui.table.cell>
                                    <x-ui.table.cell>Accessories</x-ui.table.cell>
                                    <x-ui.table.cell>$29</x-ui.table.cell>
                                    <x-ui.table.cell><a href="#" class="font-medium text-blue-600 hover:underline dark:text-blue-500">Edit</a></x-ui.table.cell>
                                </x-ui.table.row>
                            </tbody>

                            <x-slot name="footer">
                                <x-ui.table.footer>
                                    <x-ui.pagination.summary :from="1" :to="10" :total="1000" />
                                    <x-ui.pagination
                                        variant="numbered"
                                        :current-page="3"
                                        :last-page="100"
                                        :page-items="[1, 2, 3, '...', 5]"
                                        :page-urls="[1 => '#page-1', 2 => '#page-2', 3 => '#page-3', 4 => '#page-4', 5 => '#page-5']"
                                        previous-url="#page-2"
                                        next-url="#page-4"
                                        aria-label="Table pagination"
                                    />
                                </x-ui.table.footer>
                            </x-slot>
                        </x-ui.table>
                    </div>
                </x-ui.card>
            </section>
        <x-ui.modal id="demo-modal" title="Flowbite modal">
            <p class="leading-relaxed text-gray-500 dark:text-gray-400">
                Це reusable modal-компонент. Його можна наповнити будь-яким Blade-контентом, а відкриття та закриття обробляє Alpine.js.
            </p>
            <div class="flex items-center gap-3">
                <x-ui.button @click="$dispatch('close-modal', 'demo-modal')">Got it</x-ui.button>
                <x-ui.button variant="secondary" @click="$dispatch('close-modal', 'demo-modal')">Close</x-ui.button>
            </div>
        </x-ui.modal>

        <x-ui.modal id="popup-modal" title="Delete item" size="sm">
            <p class="leading-relaxed text-gray-500 dark:text-gray-400">
                Are you sure you want to delete this item? This action cannot be undone.
            </p>

            <x-slot name="footer">
                <x-ui.button variant="danger" @click="$dispatch('close-modal', 'popup-modal')">Delete</x-ui.button>
                <x-ui.button variant="secondary" @click="$dispatch('close-modal', 'popup-modal')">Cancel</x-ui.button>
            </x-slot>
        </x-ui.modal>
    </main>
</x-layouts.site>
