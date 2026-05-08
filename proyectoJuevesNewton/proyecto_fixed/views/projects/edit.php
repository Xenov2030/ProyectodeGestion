<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <a href="<?= url('proyectos') ?>" class="text-indigo-600 font-bold text-sm hover:underline flex items-center gap-2 mb-4">
            ← Back to Projects
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Project</h2>
        <p class="text-slate-500 mt-1">Update the parameters and team for <?= htmlspecialchars($proyecto['nombre']) ?>.</p>
    </div>

    <form action="/proyectos/editar?id=<?= $proyecto['id'] ?>" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-200 p-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <!-- Left Side: Basic Info -->
            <div class="space-y-8">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Project Name</label>
                    <input type="text" name="nombre" required value="<?= htmlspecialchars($proyecto['nombre']) ?>" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Description</label>
                    <textarea name="descripcion" rows="6" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"><?= htmlspecialchars($proyecto['descripcion']) ?></textarea>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Select Client</label>
                    <select name="cliente_id" required class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                        <?php foreach($clientes as $client): ?>
                            <option value="<?= $client['id'] ?>" <?= $proyecto['cliente_id'] == $client['id'] ? 'selected' : '' ?>><?= htmlspecialchars($client['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Right Side: Details & Team -->
            <div class="space-y-8">
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Status</label>
                        <select name="estado" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                            <option value="pendiente" <?= $proyecto['estado'] == 'pendiente' ? 'selected' : '' ?>>Pending</option>
                            <option value="en_progreso" <?= $proyecto['estado'] == 'en_progreso' ? 'selected' : '' ?>>In Progress</option>
                            <option value="finalizado" <?= $proyecto['estado'] == 'finalizado' ? 'selected' : '' ?>>Finished</option>
                            <option value="cancelado" <?= $proyecto['estado'] == 'cancelado' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Priority</label>
                        <select name="prioridad" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                            <option value="baja" <?= $proyecto['prioridad'] == 'baja' ? 'selected' : '' ?>>Low</option>
                            <option value="media" <?= $proyecto['prioridad'] == 'media' ? 'selected' : '' ?>>Medium</option>
                            <option value="alta" <?= $proyecto['prioridad'] == 'alta' ? 'selected' : '' ?>>High</option>
                            <option value="urgente" <?= $proyecto['prioridad'] == 'urgente' ? 'selected' : '' ?>>Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Start Date</label>
                        <input type="date" name="fecha_inicio" value="<?= $proyecto['fecha_inicio'] ?>" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">End Date</label>
                        <input type="date" name="fecha_fin" value="<?= $proyecto['fecha_fin'] ?>" class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Assign Employees</label>
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 max-h-48 overflow-y-auto space-y-2">
                        <?php foreach($empleados as $emp): ?>
                        <label class="flex items-center gap-3 p-2 hover:bg-white rounded-xl transition-colors cursor-pointer group">
                            <input type="checkbox" name="empleados[]" value="<?= $emp['id'] ?>" <?= in_array($emp['id'], $asignados) ? 'checked' : '' ?> class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 transition-all">
                            <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600"><?= htmlspecialchars($emp['nombre']) ?></span>
                            <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter ml-auto"><?= ucfirst($emp['rol_nombre']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex justify-end pt-10 mt-10 border-t border-slate-100">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-12 py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-indigo-200 active:scale-95">
                Update Project
            </button>
        </div>

    </form>
</div>