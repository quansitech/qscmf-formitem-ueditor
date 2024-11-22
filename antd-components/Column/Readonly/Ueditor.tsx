import {ColumnReadonlyProps} from "@quansitech/antd-admin/dist/components/Column/Readonly/types";
import React, {useEffect, useState} from "react";
import "./Ueditor.scss";

export default function (props: ColumnReadonlyProps) {

    const [value, setValue] = useState(props.fieldProps.value);

    useEffect(() => {
        const div = document.createElement('div');
        div.innerHTML = props.fieldProps.value;
        setValue(div.innerText);
    }, []);


    return <>
        <div className={'article-content'} dangerouslySetInnerHTML={{__html: value}}/>
    </>
}