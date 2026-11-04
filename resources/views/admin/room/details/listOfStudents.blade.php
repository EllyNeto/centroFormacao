<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Nominal de Formandos - {{ $room->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{-- Folha de Estilos Externa Dedicada para Impressão da Lista Nominal --}}
    <link rel="stylesheet" href="{{ asset('css/print-students.css') }}?v={{ time() }}">
    {{-- Script Externo Dedicado para Ações de Impressão --}}
    <script src="{{ asset('js/print-students.js') }}?v={{ time() }}" defer></script>
</head>
<body>

    {{-- Barra de Ferramentas Superior (Apenas Visível no Navegador) --}}
    <div class="no-print-bar no-print">
        <a href="{{ route('room.show', $room->id) }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Voltar aos Detalhes
        </a>
        <div class="action-buttons-group">
            <button type="button" id="btnPrintDocument" class="btn btn-primary">
                <i class="fa fa-print"></i> Imprimir Documento
            </button>
        </div>
    </div>

    {{-- Folha de Impressão Oficial --}}
    <div class="sheet">
        {{-- Cabeçalho Institucional --}}
        <div class="doc-header">
            <h2>Centro de Formação Profissional</h2>
            <h1>Pauta / Lista Nominal de Formandos</h1>
            <p>Ano Lectivo: {{ date('Y') }} &bull; Documento Oficial de Controlo de Presenças e Frequência</p>
        </div>

        {{-- Resumo da Turma e Atribuições --}}
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Turma:</span>
                <span class="info-value">{{ $room->name }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Curso:</span>
                <span class="info-value">{{ $room->course->name ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Formador:</span>
                <span class="info-value">{{ $room->teacher->name ?? 'Não atribuído' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Sala:</span>
                <span class="info-value">
                    @if($room->classroom)
                        Sala {{ $room->classroom->number_of_classroom }}
                    @else
                        Não atribuída
                    @endif
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Turno / Horário:</span>
                <span class="info-value">{{ $room->shift }} &bull; {{ \Carbon\Carbon::parse($room->start_time)->format('H:i') }} às {{ \Carbon\Carbon::parse($room->end_time)->format('H:i') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Dias da Semana:</span>
                <span class="info-value">{{ is_array($room->days_of_week) ? implode(', ', $room->days_of_week) : $room->days_of_week }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Período:</span>
                <span class="info-value">
                    {{ $room->start_date ? $room->start_date->format('d/m/Y') : 'Data de início N/D' }} 
                    a 
                    {{ $room->end_date ? $room->end_date->format('d/m/Y') : 'Data de fim N/D' }}
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Lotação:</span>
                <span class="info-value">{{ $room->occupiedSeats() }} inscritos / {{ $room->max_capacity }} vagas</span>
            </div>
        </div>

        {{-- Lista Nominal dos Formandos Matriculados ou Inscritos --}}
        @php
            // Se existirem formandos matriculados, lista-os; caso contrário, lista as inscrições aceites nesta turma
            $hasStudents = $room->students && $room->students->count() > 0;
            $hasEnrollments = $room->enrollments && $room->enrollments->count() > 0;
        @endphp

        <table class="students-table">
            <thead>
                <tr>
                    <th class="col-num text-center">Nº</th>
                    <th class="col-code">Código</th>
                    <th>Nome Completo do Formando</th>
                    <th class="col-bi">Número do Bilhete de Identidade</th>
                    <th class="col-phone">Telefone</th>
                    <th class="col-signature">Assinatura / Rubrica</th>
                </tr>
            </thead>
            <tbody>
                @if($hasStudents)
                    @foreach($room->students as $index => $student)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $student->code ?? 'N/A' }}</td>
                            <td><strong>{{ $student->name }}</strong></td>
                            <td>{{ $student->number_of_identify ?? '-' }}</td>
                            <td>{{ $student->phone ?? '-' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @elseif($hasEnrollments)
                    @foreach($room->enrollments as $index => $enrollment)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>INS-{{ str_pad($enrollment->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td><strong>{{ $enrollment->name }}</strong></td>
                            <td>{{ $enrollment->number_of_identify ?? '-' }}</td>
                            <td>{{ $enrollment->phone ?? '-' }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                @else
                    {{-- Caso ainda não existam formandos ou inscritos, imprime linhas pautadas para preenchimento manual --}}
                    @for($i = 1; $i <= max(10, (int)$room->max_capacity); $i++)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="blank-placeholder">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        {{-- Assinaturas Formais --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">O Formador Responsável</div>
                <small class="signature-name">{{ $room->teacher->name ?? 'Assinatura' }}</small>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-title">Coordenação Pedagógica / Direção</div>
                <small class="signature-name">Carimbo e Assinatura</small>
            </div>
        </div>

        {{-- Rodapé Informativo --}}
        <div class="doc-footer">
            <span>Emitido em: {{ date('d/m/Y \à\s H:i') }}</span>
            <span>Sistema Integrado de Gestão do Centro de Formação</span>
        </div>
    </div>

</body>
</html>
