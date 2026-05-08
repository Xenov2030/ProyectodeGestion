<div class="max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Nuevo Usuario</h2>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="/proyectos/gestor-pro/public/users/create" method="POST">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="text" name="telefono" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                <select name="rol" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="superadmin">Super Admin</option>
                    <option value="administrativo">Administrativo</option>
                    <option value="profesional">Profesional</option>
                    <option value="cliente">Cliente</option>
                    <option value="prospecto">Prospecto</option>
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <a href="/proyectos/gestor-pro/public/users" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancelar</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Guardar</button>
            </div>

        </form>
    </div>
</div>