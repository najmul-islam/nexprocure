<x-app-layout>
    <x-breadcrumb :links="['Suppliers' => route('suppliers.index'), 'Edit Supplier' => null]" />

    <div class="flex justify-between items-center py-3 border-b border-gray-200">
        <h2 class="my-4 text-3xl font-extrabold">Edit Supplier</h2>
    </div>

    <div class="bg-white shadow rounded">
        <div class="border-b p-4 flex justify-between">
            <h3 class="text-xl font-bold">Supplier Information</h3>
        </div>

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="p-4">
            @csrf
            @method('PUT')

            <!-- Supplier Name -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="name" class="text-nowrap font-bold">
                    Supplier Name: <span class="text-red-700 text-xl">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ $supplier->name }}"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter supplier name" required>
            </fieldset>

            <!-- Mobile -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="mobile" class="text-nowrap font-bold">
                    Mobile No.: <span class="text-red-700 text-xl">*</span>
                </label>
                <input type="text" name="mobile" id="mobile" value="{{ $supplier->mobile }}"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter mobile number" required>
            </fieldset>

            <!-- Email -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="email" class="text-nowrap font-bold">Email:</label>
                <input type="email" name="email" id="email" value="{{ $supplier->email }}"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter email">
            </fieldset>

            <!-- Address -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label for="address" class="text-nowrap font-bold">Address:</label>
                <textarea name="address" id="address"
                    class="px-3 py-1 border border-gray-300 rounded w-full focus:ring-0 focus:outline-none focus:border-indigo-600"
                    placeholder="Enter address">{{ $supplier->address }}</textarea>
            </fieldset>

            <!-- Status -->
            <fieldset class="mb-3 grid grid-cols-[150px_1fr] gap-6 items-center">
                <label class="text-nowrap font-bold">
                    Supplier Status: <span class="text-red-700 text-xl">*</span>
                </label>
                <div class="form-group">
                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" value="active" class="accent-indigo-600 focus:outline-0"
                            {{ $supplier->status == 'active' ? 'checked' : '' }} required>
                        Active
                    </label>

                    <label class="mr-3 font-bold">
                        <input type="radio" name="status" value="inactive" class="accent-indigo-600 focus:outline-0"
                            {{ $supplier->status == 'inactive' ? 'checked' : '' }} required>
                        Inactive
                    </label>
                </div>
            </fieldset>

            <!-- Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('suppliers.index') }}"
                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded font-semibold transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit"
                    class="px-3 py-1 bg-indigo-600 text-white rounded font-semibold hover:bg-indigo-500 transition-colors duration-200">
                    Update Supplier
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
