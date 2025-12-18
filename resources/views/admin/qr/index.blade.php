<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Store QR Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Main Store QR -->
                <div
                    class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center text-center">
                    <div class="mb-6 p-4 bg-gray-50 rounded-3xl">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($url) !!}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Main Store QR</h3>
                    <p class="text-gray-500 mb-6 text-sm">Direct link to your digital menu at<br><span
                            class="font-mono text-indigo-600">{{ $url }}</span></p>

                    <div class="flex gap-4">
                        <a href="{{ route('admin.qr.download') }}"
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                            Download PNG
                        </a>
                        <button onclick="window.print()"
                            class="px-6 py-3 border border-gray-200 rounded-xl font-bold hover:bg-gray-50 transition">
                            Print
                        </button>
                    </div>
                </div>

                <!-- Table Specific QR -->
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Table / Zone QR</h3>
                    <p class="text-gray-500 mb-8 text-sm">Generate QRs for specific tables to track order locations
                        automatically.</p>

                    <form action="{{ route('admin.qr.download') }}" method="GET" class="space-y-4">
                        <div>
                            <x-input-label for="table" :value="__('Table Number / Name')" />
                            <x-text-input id="table" name="table" type="text" class="mt-1 block w-full"
                                placeholder="e.g., Table 12, Zone B" required />
                        </div>
                        <x-primary-button class="w-full justify-center py-4">
                            Generate & Download
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>