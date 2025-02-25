import React, {useState} from 'react'
import {BrowserRouter, Routes, Route, Link} from "react-router";

// Constanty
// import {} from "./../../slice/app/app.constants.js";

// Stranky
// import Home from './../home/home.container.js'

const Home = (props) => {
  return <div>Home</div>
}

const About = (props) => {
  return <div>About</div>
}

export default (props) => {
  // const [count, setCount] = useState(1)

  return (
    <BrowserRouter>
      <nav>
        <Link to="/">Home</Link>
        <Link to="/about">About</Link>
      </nav>


      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/about" element={<About />} />
      </Routes>
    </BrowserRouter>

    /*<div>
      <hr/>
      <strong>{count}</strong>

      <button onClick={() => {
        setCount(count + 1)
      }}>INCRESE</button>

      <hr/>
      <input value="ALERT" type="button" onClick={() => props.alert(Math.random())} />
    </div>*/
  )
}
