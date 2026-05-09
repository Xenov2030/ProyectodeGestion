<?php 
// views/users/edit.php 
?>

<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <a href="<?= url('users') ?>"
           class="text-indigo-600 font-bold text-sm hover:underline flex items-center gap-2 mb-4">
            ← Volver a Usuarios
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Editar Usuario</h2>
        <p class="text-slate-500 mt-1">
           Cambie la información del usuario, actualice el rol o modifique el estado de acceso.
            <span class="font-bold text-slate-700"><?= htmlspecialchars($usuario['nombre']) ?></span>
        </p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-6 px-6 py-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-medium">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= url('users/edit?id=' . $usuario['id']) ?>" method="POST"
          class="bg-white rounded-3xl shadow-xl border border-slate-200 p-10">

        <?= \app\Core\Controller::csrf_field() ?>
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Columna izquierda -->
            <div class="space-y-8">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Nombre</label>
                    <input type="text" name="nombre" required
                           value="<?= htmlspecialchars($usuario['nombre']) ?>"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Email</label>
                    <input type="email" name="email" required
                           value="<?= htmlspecialchars($usuario['email']) ?>"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">
                        Teléfono <span class="normal-case font-medium text-slate-300">(opcional)</span>
                    </label>
                    <input type="text" name="telefono"
                           value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                           placeholder="e.g. +54 9 261 000-0000"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="space-y-8">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">
                        Nueva Contraseña
                        <span class="normal-case font-medium text-slate-300">(dejar en blanco para mantener la actual)</span>
                    </label>
                    <input type="password" name="password"
                           placeholder="Solo completar si desea cambiar la contraseña"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Rol</label>
                    <select name="rol_id" required
                            class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <?php foreach($roles as $rol): ?>
                            <option value="<?= $rol['id'] ?>"
                                <?= $usuario['rol_id'] == $rol['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rol['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Estado</label>
                    <select name="estado"
                            class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <option value="activo"   <?= $usuario['estado'] === 'activo'   ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center pt-10 mt-10 border-t border-slate-100">
            <a href="<?= url('users') ?>" class="text-slate-500 hover:text-slate-700 text-sm font-bold transition-colors">
                Cancelar
            </a>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-indigo-200 active:scale-95">
                Editar Usuario
            </button>
        </div>
    </form>
</div>