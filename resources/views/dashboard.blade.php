<x-app-layout>
    <div class="py-4">
        <div class="sm:px-6 lg:px-4">
            <div class="mb-6">
                <h1 class="text-4xl font-bold">Hi, {{ auth()->user()->name }}! </h1>
                <p class="text-lg ">Welcome to Nex Procure's admin dashboard</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Total Products -->
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-2xl font-semibold">Total Products</p>
                            <p class="text-4xl font-bold">{{ $totalProducts }}</p>
                            <p class="text-lg mt-1">Total number of products in the system</p>
                        </div>
                        <div class="text-4xl">
                            <i class="fa fa-box"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Suppliers -->
                <div class="bg-purple-500 text-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-2xl font-semibold">Total Suppliers</p>
                            <p class="text-4xl font-bold">{{ $totalSuppliers }}</p>
                            <p class="text-lg mt-1">Total number of suppliers registered</p>
                        </div>
                        <div class="text-4xl">
                            <i class="fa fa-truck"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Purchases -->
                <div class="bg-green-500 text-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-2xl font-semibold">Total Purchases</p>
                            <p class="text-4xl font-bold">{{ $totalPurchases }}</p>
                            <p class="text-lg mt-1">Total number of purchases made</p>
                        </div>
                        <div class="text-4xl">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>
