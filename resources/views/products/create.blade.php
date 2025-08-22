<x-app-layout>
    <x-breadcrumb :links="['Products' => route('products.index'), 'Add Product' => null]" />
    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Add Product</h2>

    </div>

    <div class="bg-white shadow rounded">
        <div class="border-b p-4 flex justify-between">
            <h3 class="text-xl font-bold"> Products Information</h3>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="p-4">
            @csrf

            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="name" class=" text-nowrap font-bold ">Product Name: <span
                        class="text-red-700 text-xl">*</span>
                </label>
                <input type="text" name="name"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter product name" required>
            </fieldset>

            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="name" class=" text-nowrap font-bold ">Product Status: <span
                        class="text-red-700 text-xl">*</span>
                </label>
                <div class="form-group" required>
                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" class=" accent-indigo-600 focus:outline-0 " value="active"
                            checked required>
                        Active
                    </label>

                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" value="inactive" class="accent-indigo-600 focus:outline-0"
                            required>
                        Inactive
                    </label>
                </div>
            </fieldset>

            <div class="flex justify-end gap-3">
                <a href="{{ route('products.index') }}"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition-colors duration-200">Cancel</a>
                <button type="submit"
                    class="px-3 py-1 bg-indigo-600 text-white rounded font-semibold hover:bg-indigo-500 transition-colors duration-200">Crate
                    Product</button>
            </div>
        </form>
    </div>
</x-app-layout>
