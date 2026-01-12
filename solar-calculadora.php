<?php

/**
 * Plugin Name: Calculadora Solar (CL) - OnGrid + Híbrida
 * Description: Calculadora solar con pestañas: On-Grid y Híbrida (Normal/Plus). Modos: cobro mensual o consumo anual. Muestra consumo anual, kit recomendado (detalle), ahorro anual y proyección 25 años (+6% anual) y cotiza por WhatsApp. Incluye regiones/comunas (REST interno).
 * Version: 3.0.0
 * Author: Rick Dev Team
 */

if (!defined('ABSPATH')) exit;

/** =========================
 *  REST: Regiones / Comunas
 *  ========================= */
add_action('rest_api_init', function () {
  register_rest_route('sc/v1', '/regiones', [
    'methods'  => 'GET',
    'callback' => 'sc_rest_get_regiones',
    'permission_callback' => '__return_true',
  ]);
});

function sc_rest_get_regiones(WP_REST_Request $req)
{
  $data = [
    ['nombre' => 'Región Arica y Parinacota', 'comunas' => ['Arica', 'Camarones', 'Putre', 'General Lagos']],
    ['nombre' => 'Región Tarapacá', 'comunas' => ['Iquique', 'Alto Hospicio', 'Pozo Almonte', 'Camiña', 'Colchane', 'Huara', 'Pica']],
    ['nombre' => 'Región Antofagasta', 'comunas' => ['Antofagasta', 'Mejillones', 'Sierra Gorda', 'Taltal', 'Calama', 'Ollagüe', 'San Pedro de Atacama', 'Tocopilla', 'María Elena']],
    ['nombre' => 'Región Atacama', 'comunas' => ['Copiapó', 'Caldera', 'Tierra Amarilla', 'Chañaral', 'Diego de Almagro', 'Vallenar', 'Alto del Carmen', 'Freirina', 'Huasco']],
    ['nombre' => 'Región Coquimbo', 'comunas' => ['La Serena', 'Coquimbo', 'Andacollo', 'La Higuera', 'Paiguano', 'Vicuña', 'Illapel', 'Canela', 'Los Vilos', 'Salamanca', 'Ovalle', 'Combarbalá', 'Monte Patria', 'Punitaqui', 'Río Hurtado']],
    ['nombre' => 'Región Valparaíso', 'comunas' => ['Valparaíso', 'Casablanca', 'Concón', 'Juan Fernández', 'Puchuncaví', 'Quintero', 'Viña del Mar', 'Isla de Pascua', 'Los Andes', 'Calle Larga', 'Rinconada', 'San Esteban', 'La Ligua', 'Cabildo', 'Papudo', 'Petorca', 'Zapallar', 'Quillota', 'Calera', 'Hijuelas', 'La Cruz', 'Nogales', 'San Antonio', 'Algarrobo', 'Cartagena', 'El Quisco', 'El Tabo', 'Santo Domingo', 'San Felipe', 'Catemu', 'Llaillay', 'Panquehue', 'Putaendo', 'Santa María', 'Quilpué', 'Limache', 'Olmué', 'Villa Alemana']],
    ['nombre' => 'Región del Libertador Gral. Bernardo O’Higgins', 'comunas' => ['Rancagua', 'Codegua', 'Coinco', 'Coltauco', 'Doñihue', 'Graneros', 'Las Cabras', 'Machalí', 'Malloa', 'Mostazal', 'Olivar', 'Peumo', 'Pichidegua', 'Quinta de Tilcoco', 'Rengo', 'Requínoa', 'San Vicente', 'Pichilemu', 'La Estrella', 'Litueche', 'Marchihue', 'Navidad', 'Paredones', 'San Fernando', 'Chépica', 'Chimbarongo', 'Lolol', 'Nancagua', 'Palmilla', 'Peralillo', 'Placilla', 'Pumanque', 'Santa Cruz']],
    ['nombre' => 'Región del Maule', 'comunas' => ['Talca', 'Constitución', 'Curepto', 'Empedrado', 'Maule', 'Pelarco', 'Pencahue', 'Río Claro', 'San Clemente', 'San Rafael', 'Cauquenes', 'Chanco', 'Pelluhue', 'Curicó', 'Hualañé', 'Licantén', 'Molina', 'Rauco', 'Romeral', 'Sagrada Familia', 'Teno', 'Vichuquén', 'Linares', 'Colbún', 'Longaví', 'Parral', 'Retiro', 'San Javier', 'Villa Alegre', 'Yerbas Buenas']],
    ['nombre' => 'Región de Ñuble', 'comunas' => ['Cobquecura', 'Coelemu', 'Ninhue', 'Portezuelo', 'Quirihue', 'Ránquil', 'Treguaco', 'Bulnes', 'Chillán Viejo', 'Chillán', 'El Carmen', 'Pemuco', 'Pinto', 'Quillón', 'San Ignacio', 'Yungay', 'Coihueco', 'Ñiquén', 'San Carlos', 'San Fabián', 'San Nicolás']],
    ['nombre' => 'Región del Biobío', 'comunas' => ['Concepción', 'Coronel', 'Chiguayante', 'Florida', 'Hualqui', 'Lota', 'Penco', 'San Pedro de la Paz', 'Santa Juana', 'Talcahuano', 'Tomé', 'Hualpén', 'Lebu', 'Arauco', 'Cañete', 'Contulmo', 'Curanilahue', 'Los Álamos', 'Tirúa', 'Los Ángeles', 'Antuco', 'Cabrero', 'Laja', 'Mulchén', 'Nacimiento', 'Negrete', 'Quilaco', 'Quilleco', 'San Rosendo', 'Santa Bárbara', 'Tucapel', 'Yumbel', 'Alto Biobío']],
    ['nombre' => 'Región de la Araucanía', 'comunas' => ['Temuco', 'Carahue', 'Cunco', 'Curarrehue', 'Freire', 'Galvarino', 'Gorbea', 'Lautaro', 'Loncoche', 'Melipeuco', 'Nueva Imperial', 'Padre las Casas', 'Perquenco', 'Pitrufquén', 'Pucón', 'Saavedra', 'Teodoro Schmidt', 'Toltén', 'Vilcún', 'Villarrica', 'Cholchol', 'Angol', 'Collipulli', 'Curacautín', 'Ercilla', 'Lonquimay', 'Los Sauces', 'Lumaco', 'Purén', 'Renaico', 'Traiguén', 'Victoria']],
    ['nombre' => 'Región de Los Ríos', 'comunas' => ['Valdivia', 'Corral', 'Lanco', 'Los Lagos', 'Máfil', 'Mariquina', 'Paillaco', 'Panguipulli', 'La Unión', 'Futrono', 'Lago Ranco', 'Río Bueno']],
    ['nombre' => 'Región de Los Lagos', 'comunas' => ['Puerto Montt', 'Calbuco', 'Cochamó', 'Fresia', 'Frutillar', 'Los Muermos', 'Llanquihue', 'Maullín', 'Puerto Varas', 'Castro', 'Ancud', 'Chonchi', 'Curaco de Vélez', 'Dalcahue', 'Puqueldón', 'Queilén', 'Quellón', 'Quemchi', 'Quinchao', 'Osorno', 'Puerto Octay', 'Purranque', 'Puyehue', 'Río Negro', 'San Juan de la Costa', 'San Pablo', 'Chaitén', 'Futaleufú', 'Hualaihué', 'Palena']],
    ['nombre' => 'Región Aisén del Gral. Carlos Ibáñez del Campo', 'comunas' => ['Coihaique', 'Lago Verde', 'Aisén', 'Cisnes', 'Guaitecas', 'Cochrane', 'O’Higgins', 'Tortel', 'Chile Chico', 'Río Ibáñez']],
    ['nombre' => 'Región de Magallanes y de la Antártica Chilena', 'comunas' => ['Punta Arenas', 'Laguna Blanca', 'Río Verde', 'San Gregorio', 'Cabo de Hornos (Ex Navarino)', 'Antártica', 'Porvenir', 'Primavera', 'Timaukel', 'Natales', 'Torres del Paine']],
    ['nombre' => 'Región Metropolitana de Santiago', 'comunas' => ['Cerrillos', 'Cerro Navia', 'Conchalí', 'El Bosque', 'Estación Central', 'Huechuraba', 'Independencia', 'La Cisterna', 'La Florida', 'La Granja', 'La Pintana', 'La Reina', 'Las Condes', 'Lo Barnechea', 'Lo Espejo', 'Lo Prado', 'Macul', 'Maipú', 'Ñuñoa', 'Pedro Aguirre Cerda', 'Peñalolén', 'Providencia', 'Pudahuel', 'Quilicura', 'Quinta Normal', 'Recoleta', 'Renca', 'Santiago', 'San Joaquín', 'San Miguel', 'San Ramón', 'Vitacura', 'Puente Alto', 'Pirque', 'San José de Maipo', 'Colina', 'Lampa', 'Tiltil', 'San Bernardo', 'Buin', 'Calera de Tango', 'Paine', 'Melipilla', 'Alhué', 'Curacaví', 'María Pinto', 'San Pedro', 'Talagante', 'El Monte', 'Isla de Maipo', 'Padre Hurtado', 'Peñaflor']],
  ];
  return rest_ensure_response($data);
}

/** =========================
 *  Shortcode
 *  ========================= */
function sc_render_shortcode_kwh_mes()
{
  ob_start(); ?>

  <!-- Selector de calculadora (fuera del cuadro) -->
  <div style="max-width:1280px;margin:0 auto 10px auto;">
    <div id="sc_calc_tabs" style="display:flex;gap:10px;flex-wrap:wrap;">
      <button type="button" id="sc_tab_ongrid"
        style="padding:16px 26px;border-radius:14px;border:1px solid #d1d5db;background:#16a34a;color:#fff;font-size:18px;font-weight:800;cursor:pointer;">
        On-Grid
      </button>
      <button type="button" id="sc_tab_hybrid"
        style="padding:16px 26px;border-radius:14px;border:1px solid #d1d5db;background:#16a34a;color:#fff;font-size:18px;font-weight:800;cursor:pointer;">
        Híbrida
      </button>
    </div>

    <!-- Sub selector híbrido -->
    <div id="sc_hybrid_subtabs" style="display:none;margin-top:10px;gap:10px;flex-wrap:wrap;">
      <button type="button" id="sc_tab_hyb_std"
        style="padding:12px 20px;border-radius:12px;border:1px solid #d1d5db;background:#16a34a;color:#fff;font-size:15px;font-weight:700;cursor:pointer;">
        Híbrido
      </button>
      <button type="button" id="sc_tab_hyb_plus"
        style="padding:12px 20px;border-radius:12px;border:1px solid #d1d5db;background:#16a34a;color:#fff;font-size:15px;font-weight:700;cursor:pointer;">
        Híbrido Plus
      </button>
    </div>
  </div>

  <div id="sc-wrap" style="max-width:1280px;margin:0 auto;padding:16px;border:1px solid #e5e7eb;border-radius:12px;">

    <style>
      /* Columna 3 oculta hasta calcular */
      #sc-wrap #sc-right-2 {
        display: none;
      }

      #sc-wrap.sc-show-savings #sc-right-2 {
        display: block;
      }

      /* Grid estable (NO lo pises con inline flex) */
      #sc-body {
        display: grid;
        gap: 16px;
        align-items: start;
        grid-template-columns: 1fr;
      }

      @media (min-width:880px) {
        #sc-body {
          grid-template-columns: 1fr 320px;
        }
      }

      @media (min-width:1200px) {
        #sc-body {
          grid-template-columns: 1fr 320px 340px;
        }
      }

      #sc-right {
        width: 320px;
      }

      #sc-right-2 {
        width: 340px;
      }

      /* Un poquito de “UI jugosa” en tabs */
      .sc-tab-on {
        background: #16a34a !important;
        color: #fff !important;
      }

      .sc-tab-off {
        background: #fff !important;
        color: #111 !important;
      }
    </style>

    <h3 style="margin:0 0 12px;">Simula tu consumo, descubre tu ahorro</h3>
    <p style="margin:0 0 12px;font-size:14px;color:#555;">
      Calcula tu consumo anual (kWh/año), recibe un kit recomendado y mira tu ahorro estimado.
    </p>

    <div id="sc-body">

      <!-- Columna 1 -->
      <div id="sc-left" style="min-width:300px;">

        <!-- Selector de modo A/B -->
        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:8px;">
          <label style="display:flex;align-items:center;gap:8px;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;cursor:pointer;">
            <input type="radio" name="sc_mode" value="A" checked>
            <span><b>Cotiza con cobro mensual</b></span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;border:1px solid #d1d5db;border-radius:8px;padding:8px 12px;cursor:pointer;">
            <input type="radio" name="sc_mode" value="B">
            <span><b>Cotiza con consumo anual</b></span>
          </label>
        </div>

        <!-- MODO A -->
        <div id="sc_block_a" style="border:1px dashed #d1d5db;border-radius:10px;padding:12px;">
          <label style="display:block;margin:0 0 6px;">Cobro mensual (CLP)</label>
          <input id="sc_monto_clp" type="text" inputmode="decimal" placeholder="p. ej. $100.000"
            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">
          <input id="sc_tarifa_a" type="hidden" value="220">
        </div>

        <!-- MODO B -->
        <div id="sc_block_b" style="display:none;border:1px dashed #d1d5db;border-radius:10px;padding:12px;margin-top:10px;">
          <p style="margin:0 0 8px;">Ingresa tu <b>consumo mensual</b> en kWh (según boleta):</p>
          <div id="sc_mes_inputs" style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
            <?php
            $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            foreach ($meses as $i => $m) {
              echo '<div>';
              echo '<label style="font-size:13px;display:block;margin:0 0 4px;">' . esc_html($m) . '</label>';
              echo '<input type="number" id="sc_mes_' . $i . '" min="0" placeholder="kWh" style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:8px;">';
              echo '</div>';
            }
            ?>
          </div>
          <input id="sc_tarifa_b" type="hidden" value="220">
        </div>

        <!-- Datos contacto -->
        <div id="sc_contacto" style="border:1px solid #e5e7eb;border-radius:10px;padding:12px;margin-top:12px;">
          <h4 style="margin:0 0 8px;">Tus datos</h4>

          <label style="display:block;margin:0 0 6px;">Correo electrónico</label>
          <input id="sc_email" type="email" required placeholder="ej: nombre@correo.cl"
            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">

          <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:12px;">
            <div style="flex:1;min-width:160px;">
              <label style="display:block;margin:0 0 6px;">Región</label>
              <input id="sc_region" list="sc_region_list" required placeholder="Escribe o selecciona tu región…"
                style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;background:#fff;">
              <datalist id="sc_region_list"></datalist>
            </div>
            <div style="flex:1;min-width:160px;">
              <label style="display:block;margin:0 0 6px;">Comuna</label>
              <input id="sc_comuna" list="sc_comuna_list" required placeholder="Escribe o selecciona tu comuna…"
                style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;background:#fff;">
              <datalist id="sc_comuna_list"></datalist>
            </div>
          </div>

          <label style="display:block;margin:12px 0 6px;">Dirección</label>
          <input id="sc_direccion" type="text" required placeholder="Calle, número, depto."
            style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;">

          <small id="sc_geo_msg" style="display:none;color:#b45309;">
            No se pudieron cargar las regiones. Puedes escribir región y comuna manualmente.
          </small>
        </div>

        <button id="sc_btn" style="margin-top:14px;padding:12px 16px;border:0;border-radius:10px;background:#0ea5e9;color:#fff;cursor:pointer;width:100%;">
          Calcular kWh/año
        </button>
      </div>

      <!-- Columna 2 -->
      <div id="sc-right" style="max-width:100%;min-width:280px;">
        <div id="sc_result" style="border:1px solid #e5e7eb;border-radius:10px;padding:14px;">
          <h4>Resultado</h4>
          <p style="margin:0;color:#666;">Tus resultados aparecerán aquí.</p>
        </div>
      </div>

      <!-- Columna 3 -->
      <div id="sc-right-2" aria-hidden="true" style="max-width:100%;min-width:280px;">
        <div id="sc_savings" style="border:1px solid #e5e7eb;border-radius:10px;padding:18px;background:#f8fff8;">
          <h4 style="margin:0 0 14px;font-size:24px;color:#15803d;text-align:center;">
            Cotiza con nosotros y comienza ya el ahorro
          </h4>

          <div id="sc_savings_content" style="text-align:center;">
            <p style="margin:0;color:#666;">Calcula primero para ver tu ahorro estimado.</p>
          </div>

          <a id="sc_btn_cotiza" href="#"
            style="
              margin-top:16px;
              display:block;
              width:100%;
              text-align:center;
              text-decoration:none;
              font-size:20px;
              font-weight:800;
              letter-spacing:.4px;
              padding:18px 0;
              border:0;
              border-radius:14px;
              background:linear-gradient(135deg,#16a34a,#22c55e);
              color:#fff;
              cursor:not-allowed;
              opacity:.6;
              box-shadow:0 6px 14px rgba(22,163,74,.5);
              transition:transform .15s ease, box-shadow .15s ease;
            "
            aria-disabled="true" data-enabled="0">
            🚀 <span style="text-shadow:0 2px 4px rgba(0,0,0,.2)">Cotizar por WhatsApp</span>
          </a>
        </div>
      </div>

    </div>

    <script>
      (function() {
        const $ = (id) => document.getElementById(id);
        const qS = (sel) => document.querySelector(sel);

        // ===== Constantes =====
        const TARIFA = 220; // $/kWh invisible
        const R_ANUAL = 0.06; // 6% al año
        const N_ANOS = 25;
        const SC_REGIONES_URL = "<?php echo esc_url_raw(rest_url('sc/v1/regiones')); ?>";

        // WhatsApp
        const WA_PHONE = "56966024935";

        // ===== Estado =====
        const STATE = {
          calcType: "ONGRID", // ONGRID | HYBRID
          hybridTier: "STD", // STD | PLUS
          kwhAnual: 0,
          gastoAnualCLP: 0,
          kit: null,
          contacto: {
            email: "",
            region: "",
            comuna: "",
            direccion: ""
          },
          regiones: []
        };

        // ===== KITS (On-Grid) =====
        const KITS_ONGRID = [{
            potencia: 2000,
            precio: 3500000,
            contado: 3150000,
            cuota: 291667,
            incluye: [
              "Inversor SOLIS S6-GR1P2K-M", "4 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 3000,
            precio: 4150000,
            contado: 3735000,
            cuota: 345833,
            incluye: [
              "Inversor SOLIS S6-GR1P3K-M", "5 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 4000,
            precio: 4950000,
            contado: 4455000,
            cuota: 412500,
            incluye: [
              "Inversor SOLIS S6-GR1P4K-M", "7 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 5000,
            precio: 5700000,
            contado: 5130000,
            cuota: 475000,
            incluye: [
              "Inversor SOLIS S6-GR1P5K-M", "9 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 6000,
            precio: 6600000,
            contado: 5940000,
            cuota: 550000,
            incluye: [
              "Inversor SOLIS S6-GR1P6K-M", "10 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 8000,
            precio: 8150000,
            contado: 7335000,
            cuota: 679167,
            incluye: [
              "Inversor SOLIS S6-GR1P8K-M", "14 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 10000,
            precio: 8400000,
            contado: 7560000,
            cuota: 700000,
            incluye: [
              "Inversor SOLIS S6-GR1P10K-M", "17 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          },
          {
            potencia: 15000,
            precio: 12850000,
            contado: 11565000,
            cuota: 1070833,
            incluye: [
              "Inversor CANADIAN trifásico 15kW V1", "27 paneles de 550-590 W", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ]
          }
        ];

        // ===== KITS (Híbrido STD) =====
        const KITS_HYB_STD = [{
            potencia: 5000,
            precio: 8900000,
            contado: 8010000,
            cuota: 741667,
            incluye: [
              "Inversor SOLIS S6-EH1P5K-L-PLUS", "9 paneles de 550-590 W", "Batería litio 5kWh 48V EOS SOLUNA", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 6000,
            precio: 9550000,
            contado: 8595000,
            cuota: 795833,
            incluye: [
              "Inversor SOLIS S6-EH1P6K-L-PLUS", "10 paneles de 550-590 W", "Batería litio 5kWh 48V EOS SOLUNA", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 8000,
            precio: 10900000,
            contado: 9810000,
            cuota: 908333,
            incluye: [
              "Inversor SOLIS S6-EH1P8K-L-PLUS", "14 paneles de 550-590 W", "Batería litio 5kWh 48V EOS SOLUNA", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 10000,
            precio: 13300000,
            contado: 11970000,
            cuota: 1108333,
            incluye: [
              "Inversor SOLIS S6-EH1P10K-L-PLUS", "18 paneles de 550-590 W", "Batería litio 5kWh 48V EOS SOLUNA", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          }
        ];

        // ===== KITS (Híbrido PLUS) =====
        const KITS_HYB_PLUS = [{
            potencia: 3000,
            precio: 9350000,
            contado: 8415000,
            cuota: 779167,
            incluye: [
              "Inversor HUAWEI FusionHome 3kW", "5 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 4000,
            precio: 9950000,
            contado: 8955000,
            cuota: 829167,
            incluye: [
              "Inversor HUAWEI FusionHome 4kW", "7 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 5000,
            precio: 10990000,
            contado: 9891000,
            cuota: 915833,
            incluye: [
              "Inversor HUAWEI FusionHome 5kW", "9 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 6000,
            precio: 11600000,
            contado: 10440000,
            cuota: 966667,
            incluye: [
              "Inversor HUAWEI FusionHome 6kW", "10 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 8000,
            precio: 12400000,
            contado: 11160000,
            cuota: 1033333,
            incluye: [
              "Inversor HUAWEI FusionHome 8kW", "14 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          },
          {
            potencia: 10000,
            precio: 13500000,
            contado: 12150000,
            cuota: 1125000,
            incluye: [
              "Inversor HUAWEI FusionHome 10kW", "17 paneles de 550-590 W", "Batería litio Luna 2000 5kWh", "Estructura de montaje coplanada",
              "Tablero eléctrico fotovoltaico", "Cables solares y canalización EMT", "Conexión a tablero con automático"
            ],
          }
        ];

        // ===== Helpers =====
        const fmt = (n) => Number(n || 0).toLocaleString("es-CL");
        const norm = (s) =>
          (s || "")
          .toString()
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .toLowerCase()
          .trim();

        function parseMoneyFlexible(str) {
          if (!str) return NaN;

          // deja solo dígitos, coma, punto y signo
          let t = ("" + str).replace(/[^0-9,.\-]/g, "").trim();

          const hasComma = t.includes(",");
          const hasDot = t.includes(".");

          // Caso Chile típico: 100.000 o 1.234.567  (punto = miles)
          if (hasDot && !hasComma) {
            t = t.replace(/\./g, "");
            return parseFloat(t);
          }

          // Caso mixto: 1.234.567,89  (miles con punto, decimal con coma)
          if (hasDot && hasComma) {
            t = t.replace(/\./g, "").replace(",", ".");
            return parseFloat(t);
          }

          // Caso: 1234,56 (coma decimal)
          if (hasComma && !hasDot) {
            t = t.replace(",", ".");
            return parseFloat(t);
          }

          // Caso simple: 1234 o 1234.56
          return parseFloat(t);
        }

        function getActiveCatalog() {
          if (STATE.calcType === "ONGRID") return KITS_ONGRID;
          return STATE.hybridTier === "PLUS" ? KITS_HYB_PLUS : KITS_HYB_STD;
        }

        function findKit(kwhAnual) {
          const catalog = getActiveCatalog();
          for (let i = catalog.length - 1; i >= 0; i--) {
            if (kwhAnual >= catalog[i].potencia) return catalog[i];
          }
          return catalog[0];
        }

        function setCotizaEnabled(on) {
          const b = $("sc_btn_cotiza");
          if (!b) return;
          b.dataset.enabled = on ? "1" : "0";
          b.style.cursor = on ? "pointer" : "not-allowed";
          b.style.opacity = on ? "1" : ".6";
          b.setAttribute("aria-disabled", on ? "false" : "true");
        }

        function showRight3(show) {
          const wrap = $("sc-wrap");
          const col = $("sc-right-2");
          if (!wrap || !col) return;
          if (show) {
            wrap.classList.add("sc-show-savings");
            col.setAttribute("aria-hidden", "false");
          } else {
            wrap.classList.remove("sc-show-savings");
            col.setAttribute("aria-hidden", "true");
          }
        }

        // ===== Tabs UI =====
        function setMainTab(which) {
          STATE.calcType = which;

          const ongridBtn = $("sc_tab_ongrid");
          const hybridBtn = $("sc_tab_hybrid");
          const hybridSub = $("sc_hybrid_subtabs");

          if (which === "ONGRID") {
            ongridBtn.classList.add("sc-tab-on");
            ongridBtn.classList.remove("sc-tab-off");
            hybridBtn.classList.add("sc-tab-off");
            hybridBtn.classList.remove("sc-tab-on");
            hybridSub.style.display = "none";
          } else {
            hybridBtn.classList.add("sc-tab-on");
            hybridBtn.classList.remove("sc-tab-off");
            ongridBtn.classList.add("sc-tab-off");
            ongridBtn.classList.remove("sc-tab-on");
            hybridSub.style.display = "flex";
          }

          // Reset de estado visual de resultado/cotiza (no borramos datos del usuario)
          $("sc_result").innerHTML = "<h4>Resultado</h4><p style='margin:0;color:#666;'>Tus resultados aparecerán aquí.</p>";
          showRight3(false);
          setCotizaEnabled(false);
        }

        function setHybridTier(tier) {
          STATE.hybridTier = tier;

          const stdBtn = $("sc_tab_hyb_std");
          const plusBtn = $("sc_tab_hyb_plus");

          if (tier === "STD") {
            stdBtn.classList.add("sc-tab-on");
            stdBtn.classList.remove("sc-tab-off");
            plusBtn.classList.add("sc-tab-off");
            plusBtn.classList.remove("sc-tab-on");
          } else {
            plusBtn.classList.add("sc-tab-on");
            plusBtn.classList.remove("sc-tab-off");
            stdBtn.classList.add("sc-tab-off");
            stdBtn.classList.remove("sc-tab-on");
          }

          $("sc_result").innerHTML = "<h4>Resultado</h4><p style='margin:0;color:#666;'>Tus resultados aparecerán aquí.</p>";
          showRight3(false);
          setCotizaEnabled(false);
        }

        // ===== Regiones / Comunas =====
        function normalizeDataset(data) {
          if (!Array.isArray(data)) return [];
          return data
            .map((o) => ({
              nombre: o.nombre || o.region || "",
              comunas: Array.isArray(o.comunas) ? o.comunas : [],
            }))
            .filter((o) => o.nombre && o.comunas.length);
        }

        function renderRegiones() {
          const dl = $("sc_region_list");
          if (!dl) return;
          dl.innerHTML = "";
          STATE.regiones.forEach((r) => {
            const opt = document.createElement("option");
            opt.value = r.nombre;
            dl.appendChild(opt);
          });
        }

        function renderComunas(regionNombre) {
          const dl = $("sc_comuna_list");
          if (!dl) return;
          dl.innerHTML = "";
          const r = STATE.regiones.find((x) => x.nombre === regionNombre);
          if (!r) return;
          r.comunas.forEach((c) => {
            const opt = document.createElement("option");
            opt.value = c;
            dl.appendChild(opt);
          });
        }

        function matchRegionCanonical(userInput) {
          const key = norm(userInput);
          const hit = STATE.regiones.find((r) => norm(r.nombre) === key);
          return hit ? hit.nombre : "";
        }

        fetch(SC_REGIONES_URL)
          .then((res) => res.json())
          .then((data) => {
            STATE.regiones = normalizeDataset(data);
            if (!STATE.regiones.length) throw new Error("dataset vacío");
            renderRegiones();
          })
          .catch((err) => {
            console.error("Error regiones/comunas:", err);
            const msg = $("sc_geo_msg");
            if (msg) msg.style.display = "inline";
          });

        // ===== Eventos =====
        document.addEventListener("change", (e) => {
          if (e.target && e.target.name === "sc_mode") {
            const val = e.target.value;
            $("sc_block_a").style.display = val === "A" ? "block" : "none";
            $("sc_block_b").style.display = val === "B" ? "block" : "none";
            $("sc_result").innerHTML = "<h4>Resultado</h4><p style='margin:0;color:#666;'>Tus resultados aparecerán aquí.</p>";
            showRight3(false);
            setCotizaEnabled(false);
          }

          if (e.target && e.target.id === "sc_region") {
            const canon = matchRegionCanonical(e.target.value || "");
            if (canon) {
              e.target.value = canon;
              renderComunas(canon);
            } else {
              $("sc_comuna_list").innerHTML = "";
            }
          }
        });

        (function bindMoneyInput() {
          const el = document.getElementById("sc_monto_clp");
          if (!el) return;

          el.addEventListener("input", () => {
            // toma solo dígitos
            const digits = (el.value || "").replace(/\D/g, "");
            if (!digits) {
              el.value = "";
              return;
            }
            // formatea como CLP (sin decimales)
            el.value = "$" + Number(digits).toLocaleString("es-CL");
          });
        })();


        document.addEventListener("click", (e) => {
          if (e.target && e.target.id === "sc_tab_ongrid") setMainTab("ONGRID");
          if (e.target && e.target.id === "sc_tab_hybrid") setMainTab("HYBRID");
          if (e.target && e.target.id === "sc_tab_hyb_std") setHybridTier("STD");
          if (e.target && e.target.id === "sc_tab_hyb_plus") setHybridTier("PLUS");
        });

        // ===== Calcular =====
        document.addEventListener("click", (e) => {
          if (!(e.target && e.target.id === "sc_btn")) return;

          // Validación contacto
          const email = ($("sc_email")?.value || "").trim();
          let region = ($("sc_region")?.value || "").trim();
          const comuna = ($("sc_comuna")?.value || "").trim();
          const direccion = ($("sc_direccion")?.value || "").trim();

          if (!email || !region || !comuna || !direccion) {
            alert("Completa correo, región, comuna y dirección.");
            return;
          }
          if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert("El correo electrónico no es válido.");
            return;
          }
          if (STATE.regiones.length) {
            const canon = matchRegionCanonical(region);
            if (!canon) {
              alert("Selecciona una región válida de la lista.");
              return;
            }
            region = canon;
            $("sc_region").value = canon;
          }

          const mode = qS('input[name="sc_mode"]:checked')?.value || "A";

          let kwhAnual = 0;
          let detalle = "";
          let gastoAnualCLP = 0;

          if (mode === "A") {
            const monto = parseMoneyFlexible(($("sc_monto_clp")?.value || "0"));
            if (!monto || monto <= 0) {
              alert("Ingresa el cobro mensual (> 0).");
              return;
            }
            const kwhMes = monto / TARIFA;
            kwhAnual = Math.round(kwhMes * 12);
            gastoAnualCLP = Math.round(monto * 12);

            detalle = `
              <p style="margin:0 0 6px;"><b>Cobro mensual:</b> $${fmt(monto)} CLP</p>
              <p style="margin:0 0 6px;"><b>Consumo estimado:</b> ${fmt(Math.round(kwhMes))} kWh/mes → ${fmt(kwhAnual)} kWh/año</p>
            `;
          } else {
            let suma = 0,
              llenos = 0;
            for (let i = 0; i < 12; i++) {
              const v = parseFloat((($("sc_mes_" + i)?.value || "") + "").replace(",", "."));
              if (!isNaN(v) && v >= 0) {
                suma += v;
                llenos++;
              }
            }
            if (llenos !== 12) {
              alert("Completa los 12 meses en kWh.");
              return;
            }
            kwhAnual = Math.round(suma);
            gastoAnualCLP = Math.round(kwhAnual * TARIFA);

            detalle = `<p style="margin:0 0 6px;"><b>Consumo total:</b> ${fmt(kwhAnual)} kWh/año</p>`;
          }

          const kit = findKit(kwhAnual);

          STATE.kwhAnual = kwhAnual;
          STATE.gastoAnualCLP = gastoAnualCLP;
          STATE.kit = kit;
          STATE.contacto = {
            email,
            region,
            comuna,
            direccion
          };

          // Render Columna 2

          const tipoLabel =
            STATE.calcType === "ONGRID" ?
            "On-Grid" :
            (STATE.hybridTier === "PLUS" ? "Híbrido Plus" : "Híbrido");

          $("sc_result").innerHTML = `
            <h4>Resultado</h4>
            <p style="margin:0 0 8px;"><b>Tipo:</b> ${tipoLabel}</p>
            ${detalle}
            <p style="margin:0 0 6px;"><b>Kit recomendado:</b> ${fmt(kit.potencia)} W</p>

            <div style="margin:10px 0 10px;padding:12px;border:1px solid #d1d5db;border-radius:10px;background:#f8fafc;">
              <p style="margin:0 0 8px;"><b>Incluye:</b></p>
              <ul style="margin:0 0 10px 18px;padding:0;">
                ${(kit.incluye || []).map(i => `<li>${i}</li>`).join("")}
              </ul>

              <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <div style="flex:1;min-width:180px;">
                  <small style="display:block;color:#555;">Precio lista desde</small>
                  <b>$ ${fmt(kit.precio)} CLP</b>
                </div>
                <div style="flex:1;min-width:180px;">
                  <small style="display:block;color:#555;">12 cuotas sin interés con tarjeta de crédito</small>
                  <b>$ ${fmt(kit.cuota)} /mes</b>
                </div>
                <div style="flex:1;min-width:180px;">
                  <small style="display:block;color:#555;">Pago transferencia electrónica (-10%)</small>
                  <b>$ ${fmt(kit.contado)} CLP</b>
                </div>
              </div>
            </div>
          `;

          // Proyección 25 años con aumento 6%: suma geométrica
          // Total = VP * ((1+r)^n - 1) / r
          const total25 = Math.round(gastoAnualCLP * ((Math.pow(1 + R_ANUAL, N_ANOS) - 1) / R_ANUAL));

          $("sc_savings_content").innerHTML = `
            <div style="text-align:center;">
              <p style="margin:12px 0 6px;font-size:22px;font-weight:700;color:#065f46;">
                Ahorro anual estimado
              </p>
              <p style="margin:0 0 18px;font-size:36px;font-weight:900;color:#047857;">
                $${fmt(gastoAnualCLP)} CLP
              </p>

              <p style="margin:12px 0 6px;font-size:22px;font-weight:700;color:#065f46;">
                Ahorro proyectado a 25 años (sube 6% anual)
              </p>
              <p style="margin:0 0 10px;font-size:38px;font-weight:900;color:#065f46;">
                $${fmt(total25)} CLP
              </p>

              <small style="display:block;color:#555;">
                Proyección simple: suma anual con +6% cada año (sin indexar otros factores).
              </small>
            </div>
          `;

          showRight3(true);
          setCotizaEnabled(true);
        });

        // ===== WhatsApp =====
        document.addEventListener("click", (e) => {
          const btn = e.target.closest && e.target.closest("#sc_btn_cotiza");
          if (!btn) return;

          e.preventDefault();
          if (btn.dataset.enabled !== "1") return;

          const {
            kwhAnual,
            kit,
            contacto,
            calcType,
            hybridTier,
            gastoAnualCLP
          } = STATE;

          const tipoLabel =
            calcType === "ONGRID" ?
            "On-Grid" :
            (hybridTier === "PLUS" ? "Híbrido Plus" : "Híbrido");

          const msg = [
            "Hola, quiero cotizar un kit solar ⚡",
            `• Tipo: ${tipoLabel}`,
            `• Consumo anual estimado: ${fmt(kwhAnual)} kWh/año`,
            `• Gasto anual estimado: $${fmt(gastoAnualCLP)} CLP`,
            `• Kit recomendado: ${fmt(kit.potencia)} W (precio lista desde $${fmt(kit.precio)} CLP)`,
            `• 12 cuotas s/ interés: $${fmt(kit.cuota)}/mes`,
            `• Pago transferencia electrónica (-10%): $${fmt(kit.contado)} CLP`,
            "",
            "Mis datos:",
            `• Correo: ${contacto.email}`,
            `• Región: ${contacto.region}`,
            `• Comuna: ${contacto.comuna}`,
            `• Dirección: ${contacto.direccion}`,
          ].join("\n");

          // Nota realista: en PC, WhatsApp Desktop a veces NO precarga el texto por restricciones del handler.
          // Lo más confiable en escritorio: web.whatsapp.com
          const urlWeb = `https://web.whatsapp.com/send?phone=${WA_PHONE}&text=${encodeURIComponent(msg)}`;
          const urlAPI = `https://api.whatsapp.com/send?phone=${WA_PHONE}&text=${encodeURIComponent(msg)}`;

          const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
          window.open(isMobile ? urlAPI : urlWeb, "_blank");
        });

        // ===== Init =====
        $("sc_tab_ongrid").classList.add("sc-tab-on");
        $("sc_tab_hybrid").classList.add("sc-tab-off");

        $("sc_hybrid_subtabs").style.display = "none";
        $("sc_tab_hyb_std").classList.add("sc-tab-on");
        $("sc_tab_hyb_plus").classList.add("sc-tab-off");

        showRight3(false);
        setCotizaEnabled(false);
      })();
    </script>
  </div>
<?php
  return ob_get_clean();
}
add_shortcode('solar_calc', 'sc_render_shortcode_kwh_mes');
