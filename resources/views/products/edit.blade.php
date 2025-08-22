<x-app-layout>
    <x-breadcrumb :links="['Products' => route('products.index'), 'Edit Product' => null]" />

    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Edit Product</h2>
    </div>

    <div class="bg-white shadow rounded">
        <div class="border-b p-4 flex justify-between">
            <h3 class="text-xl font-bold">Product Information</h3>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" class="p-4">
            @csrf
            @method('PUT')

            <!-- Product Name -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="name" class="text-nowrap font-bold">
                    Product Name: <span class="text-red-700 text-xl">*</span>
                </label>
                <input type="text" name="name" value="{{ $product->name }}"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter product name" required>
            </fieldset>

            <!-- Product Status -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label class="text-nowrap font-bold">
                    Product Status: <span class="text-red-700 text-xl">*</span>
                </label>
                <div class="form-group" required>
                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" value="active" class="accent-indigo-600 focus:outline-0"
                            {{ $product->status == 'active' ? 'checked' : '' }} required>
                        Active
                    </label>

                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" value="inactive" class="accent-indigo-600 focus:outline-0"
                            {{ $product->status == 'inactive' ? 'checked' : '' }} required>
                        Inactive
                    </label>
                </div>
            </fieldset>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('products.index') }}"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-3 py-1 bg-indigo-600 text-white rounded font-semibold hover:bg-indigo-500 transition-colors duration-200">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
