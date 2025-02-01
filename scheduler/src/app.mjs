import express from "express"
const app = express()
import dotenv from "dotenv"
dotenv.config() 

const PORT = process.env.APP_PORT

//import db connection

import dbconnection from "../connection/connection.mjs"

// app.get('/toSchedule', (req, res) => {
//     const queryView = "SELECT * FROM fam_appointment"
//     dbconnection.query(queryView, (result, err) => {
//         if(err)
//             throw err
//         if(result.length > 0)
//         {
//             res.send(result)
//         }
//     })
// })

app.listen(PORT, ()=> {
    try 
    {
           
            dbconnection.connect((err) => {
                if(err)
                    throw err
                setInterval(function(){
                    dbconnection.query("SELECT FU.email as email,FA.fullname as patientname FROM fam_appointment FA LEFT JOIN fam_user FU ON FA.uid = FU.user_id;", (err, result) => {
                        if(err)
                            throw err
                        console.log(`Email Sent ${result[0]['email']} w/ patient ${result[0]['patientname']}`)
                    })
                },1000)
                console.log(`App is listening to the port ${PORT} and  DB ${process.env.APP_DATABASE}`)
            })
    }
    catch(err)
    {
        console.error(`App is not connected`)
    }
})