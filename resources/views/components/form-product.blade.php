@props(['users'])

<form
	method="POST"
	action="{{ route('create_product') }}"
	class="w-full max-w-lg">
	@csrf
	<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full px-3">
			<select
				class="border border-gray-300 rounded-full text-gray-600 h-10 pl-5 pr-10 bg-white hover:border-gray-400 focus:outline-none appearance-none"
				name="user_id"
				id="user_id">
				<option disabled selected>Seleccione usuario</option>
				@foreach($users as $user)
				<option value="{{$user->id}}">{{ $user->name }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full px-3">
			<label
				for="product-name"
				class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
				<input
					type="text"
					id="product-name"
					name="product-name"
					required
					autofocus
					class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900 w-full py-3 px-4"
					placeholder="Nombre del producto" />
				<span
					class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
					Nombre del producto
				</span>
			</label>
		</div>
	</div>
	<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full px-3">
			<label
				for="price"
				class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
				<input
					type="number"
					step="0.01"
					id="price"
					name="price"
					required
					class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900 w-full py-3 px-4"
					placeholder="Precio" />
				<span
					class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
					Precio
				</span>
			</label>
		</div>
	</div>
	<div class="flex flex-wrap -mx-3 mb-6">
		<div class="w-full px-3">
			<label
				for="date"
				class="relative block rounded-md border border-gray-200 shadow-sm focus-within:border-blue-600 focus-within:ring-1 focus-within:ring-blue-600">
				<input
					type="date"
					id="date"
					name="date"
					required
					class="peer border-none bg-transparent placeholder-transparent focus:border-transparent focus:outline-none focus:ring-0 text-gray-900 w-full py-3 px-4"
					placeholder="Fecha" />
				<span
					class="pointer-events-none absolute start-2.5 top-0 -translate-y-1/2 bg-white p-0.5 text-xs text-gray-700 transition-all peer-placeholder-shown:top-1/2 peer-placeholder-shown:text-sm peer-focus:top-0 peer-focus:text-xs">
					Fecha
				</span>
			</label>
		</div>
	</div>
</form>
