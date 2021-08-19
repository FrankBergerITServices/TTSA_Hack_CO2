/*
  MKR ENV Shield - Read Sensors

  This example reads the sensors on-board the MKR ENV shield
  and prints them to the Serial Monitor once a second.

  The circuit:
  - Arduino MKR board
  - Arduino MKR ENV Shield attached

  This example code is in the public domain.
*/

#include <MKRWAN.h>
#include <Wire.h>
#include <SparkFun_SCD30_Arduino_Library.h>
#include "arduino_secrets.h"

LoRaModem modem;
SCD30 airSensorSCD30; // Object SDC30 Sensor

float co2;
float temperature;
float humidity;

void setup() {
  Serial.begin(115200);
  while (!Serial);

  Wire.begin(); // ---- Initialisiere den I2C-Bus 

  if (airSensorSCD30.begin() == false) {
    Serial.println("The SCD30 did not respond. Please check wiring."); 
    while(1) {
      yield(); 
      delay(1);
    } 
  }

  airSensorSCD30.setAutoSelfCalibration(false); // Sensirion no auto calibration

  airSensorSCD30.setMeasurementInterval(2);     // CO2-Messung alle 5 s

  // change this to your regional band (eg. US915, AS923, ...)
  if (!modem.begin(EU868)) {
    Serial.println("Failed to start module");
    while (1) {}
  };

  connectToLoRaWAN();
}

void connectToLoRaWAN(){
  Serial.println("Connecting...");
  int connected = modem.joinOTAA(APP_EUI, APP_KEY);

  if (!connected) {
    Serial.println("Something went wrong; are you indoor? Move near a window and retry");
    while (1) {}
  }

  delay(5000);
}

void sendSensorValues(){
  Serial.println("Sending message...");
  modem.setPort(3);
  modem.beginPacket();
  modem.write<float>(co2);
  modem.write<float>(temperature);
  modem.write<float>(humidity);
    
  int error = modem.endPacket(true);
  
  if (error > 0) {
    Serial.println("Message sent correctly!");
  } else {
    Serial.println("Error sending message :(");
  }

  Serial.println();
}

void loop() {
  // read all the sensor values
  co2         = airSensorSCD30.getCO2();
  temperature = airSensorSCD30.getTemperature();
  humidity    = airSensorSCD30.getHumidity();

  // print each of the sensor values
  Serial.print("CO2 = ");
  Serial.print(co2);
  Serial.println(" ppm");
  
  Serial.print("Temperature = ");
  Serial.print(temperature);
  Serial.println(" °C");

  Serial.print("Humidity    = ");
  Serial.print(humidity);
  Serial.println(" %");
  
  // print an empty line
  Serial.println();

  sendSensorValues();

  // wait 1 second to print again
  delay(60000);
}
