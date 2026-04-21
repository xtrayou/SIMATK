import xml.etree.ElementTree as ET
import os

tree = ET.parse('C:/laragon/www/skripsi/simatk/public/diagram.xml')
root = tree.getroot()

def build_module(name, parent_ids):
    new_root = ET.Element('mxGraphModel', {
        'dx': '1000', 'dy': '1000', 'grid': '1', 'gridSize': '10', 'guides': '1',
        'tooltips': '1', 'connect': '1', 'arrows': '1', 'fold': '1', 'page': '1',
        'pageScale': '1', 'pageWidth': '1169', 'pageHeight': '827', 'math': '0', 'shadow': '0'
    })
    root_node = ET.SubElement(new_root, 'root')
    ET.SubElement(root_node, 'mxCell', {'id': '0'})
    ET.SubElement(root_node, 'mxCell', {'id': '1', 'parent': '0'})
    
    # Track all accepted nodes (parents and children)
    accepted_ids = set()
    accepted_cells = []
    
    for cell in root.find('root'):
        cell_id = cell.get('id')
        if cell_id in ['0', '1']: continue
        
        # Is it a parent?
        if cell_id in parent_ids:
            accepted_ids.add(cell_id)
            accepted_cells.append(cell)
            continue
            
        # Is it a child?
        parent_attr = cell.get('parent')
        if parent_attr in parent_ids:
            accepted_ids.add(cell_id)
            accepted_cells.append(cell)
            continue
            
    # Second pass for edges
    # We want edges where both source and target (if they exist) point to accepted IDs
    # But wait, edges might connect directly to parent IDs or child IDs.
    for cell in root.find('root'):
        cell_id = cell.get('id')
        if cell_id in ['0', '1']: continue
        
        if cell.get('edge') == '1' or cell.get('source') or cell.get('target'):
            source = cell.get('source')
            target = cell.get('target')
            parent_attr = cell.get('parent')
            
            # An edge must belong to the drawing surface parent="1" OR be a connection between nodes
            # If source and target are defined, both must be in accepted_ids
            # Not all edges have both source and target explicitly.
            # But in Draw.io, usually edges connect source and target.
            # Let's say if it has source and target, both must be in accepted.
            if source and target:
                if source in accepted_ids and target in accepted_ids:
                    accepted_cells.append(cell)
            elif source:
                if source in accepted_ids:
                    accepted_cells.append(cell)
            elif target:
                if target in accepted_ids:
                    accepted_cells.append(cell)
            else:
                 # Check if it has a style indicating it's an edge, but this diagram has source and target explicit.
                 # Edge labels like "uses" might have their parent as the edge ID itself!
                 pass
                 
    # Support edge labels:
    # A label cell has parent = the edge's id.
    # So we collect all accepted edge IDs, then add their labels.
    all_accepted_ids = {c.get('id') for c in accepted_cells}
    for cell in root.find('root'):
        if cell.get('parent') in all_accepted_ids and cell not in accepted_cells:
            accepted_cells.append(cell)
            
    # Add to new root
    for cell in accepted_cells:
        # Create a copy so we don't modify the original tree
        root_node.append(cell)
        
    ET.ElementTree(new_root).write(f'C:/laragon/www/skripsi/simatk/public/{name}.xml', encoding='utf-8', xml_declaration=False)

# Auth Group
build_module('modul_auth', [
    'DP9Rxo7SRgFSSI8ZWij6-528', # BaseController
    'B4e5OV3R-pKaYSmXN-Kw-528', # AuthController
    'TBGjg_RJWQJYpiLmdW9P-562', # PenggunaController
    'TBGjg_RJWQJYpiLmdW9P-548', # PenggunaModel
    'new_RoleModel_Container'   # RoleModel
])

# Master Group
build_module('modul_master', [
    'DP9Rxo7SRgFSSI8ZWij6-528', # BaseController
    'TBGjg_RJWQJYpiLmdW9P-530', # KategoriController
    'TBGjg_RJWQJYpiLmdW9P-552', # BarangController
    '2O_1-1bbosKi86xOqmSn-529', # KodeBarangController
    'zR65ipiY7b8P6UugeGaI-536', # ApibarangController
    'TBGjg_RJWQJYpiLmdW9P-569', # KategoriModel
    'TBGjg_RJWQJYpiLmdW9P-585', # BarangModel
    '0oDJ993HJmktGicfyEoB-527'  # KodeBarangModel
])

# Transaksi Group
build_module('modul_transaksi', [
    'DP9Rxo7SRgFSSI8ZWij6-528', # BaseController
    'bmVpz-RR-LXL3VVtkCfk-530', # StokController
    'zR65ipiY7b8P6UugeGaI-561', # PermintaanController
    'zR65ipiY7b8P6UugeGaI-575', # LaporanController
    'zR65ipiY7b8P6UugeGaI-528', # MutasiStokModel
    'zR65ipiY7b8P6UugeGaI-553', # PermintaanModel
    'cOMa581StmLsYxAa2iuP-532', # ItemPermintaanModel
    'new_LaporanService_Container' # LaporanService
])
