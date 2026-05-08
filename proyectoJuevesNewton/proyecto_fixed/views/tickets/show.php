<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    
    <!-- Left: Conversation Thread -->
    <div class="lg:col-span-2 space-y-8">
        <div class="mb-6">
            <a href="<?= url('tickets') ?>" class="text-indigo-600 font-bold text-sm hover:underline flex items-center gap-2 mb-4">
                ← Back to Tickets
            </a>
            <div class="flex items-center gap-4">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight"><?= htmlspecialchars($ticket['titulo']) ?></h2>
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter bg-slate-100 text-slate-600">
                    ID #<?= $ticket['id'] ?>
                </span>
            </div>
        </div>

        <!-- Initial Message -->
        <div class="bg-indigo-50 border border-indigo-100 rounded-3xl p-8 relative">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                    <?= substr($ticket['usuario_nombre'], 0, 1) ?>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($ticket['usuario_nombre']) ?></p>
                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest"><?= date('M d, Y - H:i', strtotime($ticket['created_at'])) ?></p>
                </div>
                <div class="ml-auto">
                    <span class="px-3 py-1 bg-white text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-widest">Initial Issue</span>
                </div>
            </div>
            <p class="text-slate-700 leading-relaxed font-medium">
                <?= nl2br(htmlspecialchars($ticket['descripcion'])) ?>
            </p>
        </div>

        <!-- Messages Loop -->
        <div class="space-y-6 relative before:absolute before:left-5 before:top-0 before:bottom-0 before:w-0.5 before:bg-slate-100">
            <?php foreach($mensajes as $msg): ?>
            <div class="relative pl-12">
                <div class="absolute left-0 top-2 w-10 h-10 rounded-xl <?= $msg['rol_nombre'] === 'cliente' ? 'bg-slate-100 text-slate-600' : 'bg-emerald-600 text-white' ?> flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-white">
                    <?= substr($msg['usuario_nombre'], 0, 1) ?>
                </div>
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <p class="text-sm font-bold text-slate-900"><?= htmlspecialchars($msg['usuario_nombre']) ?></p>
                        <span class="text-[10px] text-slate-400 font-medium tracking-widest">• <?= date('M d, H:i', strtotime($msg['created_at'])) ?></span>
                        <?php if($msg['rol_nombre'] !== 'cliente'): ?>
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600 ml-auto">Team Response</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed font-medium">
                        <?= nl2br(htmlspecialchars($msg['mensaje'])) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Reply Form -->
        <?php if($ticket['estado'] !== 'cerrado'): ?>
        <div class="pt-8">
            <form action="/tickets/responder" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-200 p-8">
                <input type="hidden" name="ticket_id" value="<?= $ticket['id'] ?>">
                <label class="text-xs font-black uppercase tracking-widest text-slate-400 block mb-4">Post a Response</label>
                <textarea name="mensaje" rows="5" required placeholder="Type your message here..." class="bg-slate-50 border border-slate-200 rounded-2xl px-6 py-4 text-slate-700 font-medium focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all w-full mb-6"></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-10 py-4 rounded-2xl text-sm font-black uppercase tracking-widest transition-all shadow-lg hover:shadow-indigo-200 active:scale-95">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
        <?php else: ?>
        <div class="bg-slate-50 border border-slate-200 border-dashed rounded-3xl p-10 text-center">
            <p class="text-slate-400 font-bold">This ticket is closed. No further replies are allowed.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Sidebar Info -->
    <div class="space-y-8">
        <!-- Ticket Status -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Ticket Status</h3>
            
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Current State</p>
                    <?php 
                        $status_colors = [
                            'abierto' => 'bg-amber-100 text-amber-700',
                            'en_proceso' => 'bg-indigo-100 text-indigo-700',
                            'cerrado' => 'bg-slate-100 text-slate-700'
                        ];
                        $color = $status_colors[$ticket['estado']] ?? 'bg-slate-100 text-slate-700';
                    ?>
                    <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-tighter <?= $color ?>">
                        <?= str_replace('_', ' ', $ticket['estado']) ?>
                    </span>
                </div>

                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Priority</p>
                    <span class="text-sm font-bold <?= $ticket['prioridad'] === 'urgente' ? 'text-rose-600' : 'text-slate-800' ?>">
                        <?= strtoupper($ticket['prioridad']) ?>
                    </span>
                </div>

                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Related Project</p>
                    <p class="text-sm font-bold text-slate-800"><?= $ticket['proyecto_nombre'] ?? 'General Support' ?></p>
                </div>

                <?php if($ticket['estado'] !== 'cerrado' && \app\Core\Session::get('rol_nombre') !== 'cliente'): ?>
                <div class="pt-4 border-t border-slate-100">
                    <form action="/tickets/cerrar" method="POST" onsubmit="return confirm('Close this ticket?')">
                        <input type="hidden" name="id" value="<?= $ticket['id'] ?>">
                        <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white px-6 py-4 rounded-2xl text-xs font-black uppercase tracking-widest transition-all">
                            Mark as Resolved
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Project Team -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-6">Assigned Agent</h3>
            <?php if($ticket['asignado_nombre']): ?>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                        <?= substr($ticket['asignado_nombre'], 0, 1) ?>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900"><?= $ticket['asignado_nombre'] ?></p>
                        <p class="text-[10px] text-slate-500 font-medium">Support Agent</p>
                    </div>
                </div>
            <?php else: ?>
                <p class="text-sm text-slate-400 italic font-medium">No agent assigned yet.</p>
                <?php if(\app\Core\Session::get('rol_nombre') !== 'cliente'): ?>
                    <button class="mt-4 text-indigo-600 font-bold text-xs hover:underline">Claim Ticket →</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

</div>
