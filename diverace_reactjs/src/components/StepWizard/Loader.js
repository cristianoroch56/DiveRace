import React from "react";
import Loader from "react-loader-spinner";

const Loaderdiv = (props) => {
    return (
        <div style={{position:"absolute",
        zIndex:999,
        height:"2em",
        width:"2em",
        overflow:"visible",
        margin:"auto",
        top:0,
        left:0,
        bottom:0,
        right:0}}>
        <Loader type="Circles"
            visible={props.isShow}
            color="#4c9bf2"
            height={100}
            width={100}
            >
        </Loader>
        </div>
    )
}

export default Loaderdiv;