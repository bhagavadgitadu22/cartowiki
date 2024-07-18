function getLayerTypeName(layer)
{
    if (layer instanceof L.Marker){
        return 'Marker';
    }
    else if (layer instanceof L.Tooltip){
        return 'Tooltip';
    }
    else if (layer instanceof L.Layer){
        return 'Layer';
    }
    else{
        return 'Unknown';
    }
}

function deep_copy_array($array) {
    return array_map(function($item) {
        if (is_array($item)) {
            return deep_copy_array($item);
        }
        return $item;
    }, $array);
}
