/**
 * Formatea una fecha en formato legible para español de México,
 * incluyendo fecha y hora local.
 * 
 * @param {string|Date|null} dateString - Fecha a formatear.
 * @returns {string} - Ejemplo: "24 de octubre de 2025, 4:30 p.m."
 */
export function formatDateTime(dateString:string) {
  if (!dateString) return 'No definida'

  const date = new Date(dateString)

  return date.toLocaleString('es-MX', {
    day: '2-digit',
    month: 'long',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  })
}