<div class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Team & Clients</h2>
        <p class="text-slate-500 mt-1">Manage users, roles, and system access.</p>
    </div>
    <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])): 
        ?>
    <a href="<?= url('usuarios/crear') ?>" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-lg hover:shadow-indigo-200 flex items-center gap-2 active:scale-95">
        <span class="text-xl">+</span>
        <span>Add User</span>
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100 text-[10px] text-slate-500 uppercase tracking-widest font-black">
                    <th class="px-8 py-5">User Info</th>
                    <th class="px-8 py-5">Role / Department</th>
                    <th class="px-8 py-5 text-center">Status</th>
                    <th class="px-8 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                
                <?php foreach($usuarios as $user): ?>
                <tr class="hover:bg-slate-50/50 transition-all group">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center font-bold group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                <?= substr($user['nombre'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="text-slate-900 font-bold"><?= htmlspecialchars($user['nombre']); ?></p>
                                <p class="text-xs text-slate-500 font-medium"><?= htmlspecialchars($user['email']); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <span class="px-4 py-1.5 bg-slate-100 text-slate-700 rounded-full text-[10px] font-black uppercase tracking-tighter">
                            <?= htmlspecialchars($user['rol_nombre']); ?>
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center">
                        <?php if($user['estado'] == 'activo'): ?>
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Active</span>
                        <?php else: ?>
                            <span class="px-4 py-1.5 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black uppercase tracking-tighter">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-8 py-6 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?= url('usuarios/editar?id=' . $user['id']) ?>" class="w-10 h-10 bg-slate-50 hover:bg-indigo-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm">
                                ✏️
                            </a>
                            <?php if (in_array(\app\Core\Session::get('rol_nombre'), ['admin', 'directivo'])): ?>
                            <form action="/usuarios/eliminar" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="w-10 h-10 bg-slate-50 hover:bg-rose-600 hover:text-white rounded-xl flex items-center justify-center transition-all shadow-sm">
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
