import React from "react";
import Loader from "react-loader-spinner";

const LoadingLoader = (props) => {
    return (
        <div style={{
           /*  display: 'inline-block' */
           textAlign: 'center'
        }} >
        <Loader type="Circles"
            visible={props.isShow}
            color="#4c9bf2"
            height={50}
            width={50}
            >
        </Loader>
        </div>
    )
}

export default LoadingLoader;