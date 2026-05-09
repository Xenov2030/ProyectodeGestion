<?php // views/users/create.php ?>

<div class="max-w-4xl mx-auto">
    <div class="mb-10">
        <a href="<?= url('users') ?>"
           class="text-indigo-600 font-bold text-sm hover:underline flex items-center gap-2 mb-4">
            ← Volver a Usuarios
        </a>
        <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Nuevo usuario</h2>
        <p class="text-slate-500 mt-1">Complete la información para crear un nuevo usuario.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="mb-6 px-6 py-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-medium">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="<?= url('users/create') ?>" method="POST"
          class="bg-white rounded-3xl shadow-xl border border-slate-200 p-10">

        <?= \app\Core\Controller::csrf_field() ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            <!-- Columna izquierda -->
            <div class="space-y-8">
                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Nombre completo</label>
                    <input type="text" name="nombre" required placeholder="ej. María Pérez"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Email</label>
                    <input type="email" name="email" required placeholder="ej. mail@empresa.com"
                           class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>

                <div class="flex flex-col gap-2">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">
                        Teléfono <span class="normal-case font-medium text-slate-300">(opcional)</span>
                    </label>
                    <input type="text" name="telefono" placeholder="ej. 54 9 261 5678900"