<?php
// views/users/index.php
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Gestión de usuarios</h2>
        <p class="text-slate-500 mt-1">Gestione usuarios, roles y acceso al sistema.</p>
    </div>
    <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])):
        ?>
        <a href="<?= url('users/create') ?>" class="btn text-white fw-bold px-4 py-2 border-0 shadow-sm"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
            <i class="bi bi-plus-lg me-2"></i> NUEVO USUARIO
        </a>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <?php if (empty($usuarios)): ?>
            <div class="p-5 text-center text-muted">No hay usuarios disponibles.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table align-middle m-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">
                        <tr>
                            <th class="py-4 ps-4 border-0">USUARIO</th>
                            <th class="py-4 border-0">EMAIL</th>
                            <th class="py-4 border-0">ROL</th>
                            <th class="py-4 border-0">ESTADO</th>
                            <th class="py-4 pe-4 text-end border-0">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($usuarios as $user): ?>
                            <tr class="hover:bg-slate-50/50 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center font-bold group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            <?= substr($user['nombre'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <p class="text-slate-900 font-bold"><?= htmlspecialchars($user['nombre']); ?></p>
                                            <p class="text-xs text-slate-500 font-medium">
                                                <?= htmlspecialchars($user['email']); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-[10px] font-black uppercase tracking-tighter">
                                        <?= htmlspecialchars($user['rol_nombre']); ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if ($user['estado'] == 'activo'): ?>
                                        <span
                                            class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Activo</span>
                                    <?php else: ?>
                                        <span
                                            class="px-4 py-1.5 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= url('users/edit?id=' . $user['id']) ?>"
                                            class="w-10 h-10 bg-slate-50 hover:bg-indigo-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm">
                                            ✏️
                                        </a>
                                        <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])): ?>
                                            <form action="/usuarios/eliminar" method="POST"
                                                onsubmit="return confirm('Are you sure?')" class="inline">
                                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                                <button type="submit"
                                                    class="w-10 h-10 bg-slate-50 hover:bg-rose-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm">
                                                    🗑️
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>